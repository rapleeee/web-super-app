<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepegawaianTu\UpdateTuBeritaAcaraTindakLanjutRequest;
use App\Models\AuditLog;
use App\Models\BeritaAcara;
use App\Models\SaranaUmumBeritaAcara;
use App\Models\TuBeritaAcaraTindakLanjut;
use App\Services\KepegawaianTu\ArsipDokumenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BeritaAcaraFinalController extends Controller
{
    public function __construct(private ArsipDokumenService $arsipDokumenService) {}

    public function index(Request $request): View
    {
        $mergedRecords = $this->collectMergedRecords($request);
        $records = $this->paginateCollection($mergedRecords, 15, 'page');

        $summary = [
            'total' => $mergedRecords->count(),
            'laboran' => $mergedRecords->where('source_type', 'laboran')->count(),
            'sarana_umum' => $mergedRecords->where('source_type', 'sarana_umum')->count(),
            'baru' => $mergedRecords->where('tindak_lanjut_status', 'baru')->count(),
            'diproses' => $mergedRecords->where('tindak_lanjut_status', 'diproses')->count(),
            'selesai' => $mergedRecords->where('tindak_lanjut_status', 'selesai')->count(),
            'arsip' => $mergedRecords->where('tindak_lanjut_status', 'arsip')->count(),
        ];

        return view('kepegawaian-tu.berita-acara-final.index', [
            'records' => $records,
            'summary' => $summary,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function export(Request $request): Response
    {
        $rows = $this->collectMergedRecords($request);

        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['Tanggal', 'Sumber', 'Nama Guru', 'Ruangan/Lab', 'Kegiatan', 'Petugas', 'Status Tindak Lanjut', 'Tag']);
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['tanggal']->format('Y-m-d'),
                $row['sumber'],
                $row['nama_guru'],
                $row['ruangan'],
                $row['kegiatan'],
                $row['petugas'],
                $this->statusOptions()[$row['tindak_lanjut_status']] ?? strtoupper((string) $row['tindak_lanjut_status']),
                implode(', ', $row['tags'] ?? []),
            ]);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        AuditLog::record('tu-berita-acara-final', 'export', null, null, [
            'start_date' => $request->string('start_date')->toString(),
            'end_date' => $request->string('end_date')->toString(),
            'sumber' => $request->string('sumber')->toString(),
            'tindak_lanjut_status' => $request->string('tindak_lanjut_status')->toString(),
            'exported_rows' => $rows->count(),
        ]);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="tu-berita-acara-final.csv"');
    }

    public function updateTindakLanjut(
        UpdateTuBeritaAcaraTindakLanjutRequest $request,
        string $sourceType,
        int $sourceId
    ): RedirectResponse {
        $normalizedSourceType = $this->normalizeSourceType($sourceType);
        abort_if($normalizedSourceType === null, 404);

        $sourceRecord = $this->findSourceRecord($normalizedSourceType, $sourceId);

        $validated = $request->validated();
        $tags = $this->parseTags((string) ($validated['tags'] ?? ''));
        $status = (string) $validated['status'];

        $tindakLanjut = TuBeritaAcaraTindakLanjut::query()->firstOrNew([
            'source_type' => $normalizedSourceType,
            'source_id' => $sourceId,
        ]);

        $before = $tindakLanjut->exists ? $tindakLanjut->toArray() : null;

        $tindakLanjut->fill([
            'status' => $status,
            'catatan' => $validated['catatan'] ?? null,
            'tags' => $tags,
            'processed_by' => $status === 'baru' ? null : auth()->id(),
            'processed_at' => $status === 'baru' ? null : now(),
            'archived_at' => $status === 'arsip' ? now() : null,
        ]);
        $tindakLanjut->save();

        $record = $normalizedSourceType === 'laboran'
            ? $this->transformLaboranRecord($sourceRecord)
            : $this->transformSaranaUmumRecord($sourceRecord);

        $this->arsipDokumenService->syncFromBeritaAcara(
            $normalizedSourceType,
            $sourceId,
            $record,
            $tindakLanjut,
            auth()->id()
        );

        AuditLog::record('tu-berita-acara-final', 'tindak-lanjut', $tindakLanjut, $before, $tindakLanjut->toArray());

        return redirect()
            ->route('kepegawaian-tu.berita-acara-final.index', request()->query())
            ->with('success', 'Tindak lanjut berita acara berhasil diperbarui.');
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function collectMergedRecords(Request $request): Collection
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sourceFilter = $this->normalizeSourceType((string) $request->input('sumber', ''));
        $statusFilter = (string) $request->input('tindak_lanjut_status', '');

        $laboranRecords = collect();
        if ($sourceFilter === null || $sourceFilter === 'laboran') {
            $laboranRecords = BeritaAcara::query()
                ->with(['laboratorium', 'user'])
                ->where('status', 'final')
                ->when($startDate, fn ($query) => $query->whereDate('tanggal', '>=', $startDate))
                ->when($endDate, fn ($query) => $query->whereDate('tanggal', '<=', $endDate))
                ->get()
                ->map(fn (BeritaAcara $record): array => $this->transformLaboranRecord($record));
        }

        $saranaUmumRecords = collect();
        if ($sourceFilter === null || $sourceFilter === 'sarana_umum') {
            $saranaUmumRecords = SaranaUmumBeritaAcara::query()
                ->with(['ruangan', 'user'])
                ->where('status', 'final')
                ->when($startDate, fn ($query) => $query->whereDate('tanggal', '>=', $startDate))
                ->when($endDate, fn ($query) => $query->whereDate('tanggal', '<=', $endDate))
                ->get()
                ->map(fn (SaranaUmumBeritaAcara $record): array => $this->transformSaranaUmumRecord($record));
        }

        $records = $laboranRecords
            ->concat($saranaUmumRecords)
            ->sortByDesc(fn (array $item) => $item['tanggal'])
            ->values();

        if ($records->isEmpty()) {
            return $records;
        }

        $followUps = TuBeritaAcaraTindakLanjut::query()
            ->where(function ($query) use ($records): void {
                $records
                    ->map(fn (array $record): array => ['source_type' => $record['source_type'], 'source_id' => $record['source_id']])
                    ->unique(fn (array $pair): string => $pair['source_type'].':'.$pair['source_id'])
                    ->values()
                    ->each(function (array $pair) use ($query): void {
                        $query->orWhere(function ($subQuery) use ($pair): void {
                            $subQuery->where('source_type', $pair['source_type'])
                                ->where('source_id', $pair['source_id']);
                        });
                    });
            })
            ->get()
            ->keyBy(fn (TuBeritaAcaraTindakLanjut $item): string => $item->source_type.':'.$item->source_id);

        $records = $records->map(function (array $record) use ($followUps): array {
            $key = $record['source_type'].':'.$record['source_id'];
            /** @var TuBeritaAcaraTindakLanjut|null $followUp */
            $followUp = $followUps->get($key);

            $record['tindak_lanjut_status'] = $followUp?->status ?? 'baru';
            $record['tindak_lanjut_catatan'] = $followUp?->catatan;
            $record['tags'] = $followUp?->tags ?? [];

            return $record;
        });

        if ($statusFilter !== '') {
            $records = $records
                ->filter(fn (array $record): bool => $record['tindak_lanjut_status'] === $statusFilter)
                ->values();
        }

        return $records;
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $items
     */
    private function paginateCollection(Collection $items, int $perPage, string $pageName): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $pagedItems = $items->forPage($currentPage, $perPage)->values();

        return new LengthAwarePaginator(
            $pagedItems,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * @return array<string, string>
     */
    private function statusOptions(): array
    {
        return [
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'arsip' => 'Arsip',
        ];
    }

    private function normalizeSourceType(string $sourceType): ?string
    {
        $normalized = str_replace('-', '_', strtolower(trim($sourceType)));

        if ($normalized === '') {
            return null;
        }

        return in_array($normalized, ['laboran', 'sarana_umum'], true) ? $normalized : null;
    }

    private function findSourceRecord(string $sourceType, int $sourceId): BeritaAcara|SaranaUmumBeritaAcara
    {
        if ($sourceType === 'laboran') {
            return BeritaAcara::query()
                ->with(['laboratorium', 'user'])
                ->where('status', 'final')
                ->findOrFail($sourceId);
        }

        return SaranaUmumBeritaAcara::query()
            ->with(['ruangan', 'user'])
            ->where('status', 'final')
            ->findOrFail($sourceId);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformLaboranRecord(BeritaAcara $record): array
    {
        return [
            'source_type' => 'laboran',
            'source_id' => $record->id,
            'id' => $record->id,
            'sumber' => 'Laboran',
            'tanggal' => $record->tanggal,
            'nama_guru' => $record->nama_guru,
            'ruangan' => $record->laboratorium?->nama ?? '-',
            'kegiatan' => $record->kegiatan ?? '-',
            'petugas' => $record->user?->name ?? '-',
            'route_detail' => route('laboran.berita-acara.show', $record),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function transformSaranaUmumRecord(SaranaUmumBeritaAcara $record): array
    {
        return [
            'source_type' => 'sarana_umum',
            'source_id' => $record->id,
            'id' => $record->id,
            'sumber' => 'Sarana Umum',
            'tanggal' => $record->tanggal,
            'nama_guru' => $record->nama_guru,
            'ruangan' => $record->ruangan?->nama ?? '-',
            'kegiatan' => $record->kegiatan ?? '-',
            'petugas' => $record->user?->name ?? '-',
            'route_detail' => route('sarana-umum.berita-acara.show', $record),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function parseTags(string $tagsRaw): array
    {
        if ($tagsRaw === '') {
            return [];
        }

        $tags = collect(explode(',', $tagsRaw))
            ->map(fn (string $tag): string => trim($tag))
            ->filter(fn (string $tag): bool => $tag !== '')
            ->map(fn (string $tag): string => strtolower($tag))
            ->unique()
            ->values()
            ->all();

        return $tags;
    }
}
