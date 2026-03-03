<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepegawaianTu\UpdateTuArsipDokumenRequest;
use App\Models\AuditLog;
use App\Models\TuArsipDokumen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArsipDokumenController extends Controller
{
    public function index(Request $request): View
    {
        $arsipDokumens = TuArsipDokumen::query()
            ->with('archiver')
            ->when($request->filled('module'), fn ($query) => $query->where('module', $request->string('module')->toString()))
            ->when($request->filled('status_sumber'), fn ($query) => $query->where('status_sumber', $request->string('status_sumber')->toString()))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('judul', 'like', "%{$search}%")
                        ->orWhere('nomor_dokumen', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('retensi'), function ($query) use ($request): void {
                $retensi = $request->string('retensi')->toString();

                if ($retensi === 'expired') {
                    $query->whereNotNull('retensi_sampai')
                        ->whereDate('retensi_sampai', '<', now()->toDateString());
                }

                if ($retensi === 'active') {
                    $query->where(function ($subQuery): void {
                        $subQuery->whereNull('retensi_sampai')
                            ->orWhereDate('retensi_sampai', '>=', now()->toDateString());
                    });
                }
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => TuArsipDokumen::query()->count(),
            'berstatus_final' => TuArsipDokumen::query()->where('status_sumber', 'final')->count(),
            'berstatus_arsip' => TuArsipDokumen::query()->where('status_sumber', 'arsip')->count(),
            'retensi_expired' => TuArsipDokumen::query()
                ->whereNotNull('retensi_sampai')
                ->whereDate('retensi_sampai', '<', now()->toDateString())
                ->count(),
        ];

        $modules = TuArsipDokumen::query()
            ->select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $statusSumberOptions = TuArsipDokumen::query()
            ->select('status_sumber')
            ->whereNotNull('status_sumber')
            ->distinct()
            ->orderBy('status_sumber')
            ->pluck('status_sumber');

        return view('kepegawaian-tu.arsip-digital.index', [
            'arsipDokumens' => $arsipDokumens,
            'stats' => $stats,
            'modules' => $modules,
            'statusSumberOptions' => $statusSumberOptions,
            'isPrivilegedUser' => $this->isPrivilegedUser(),
        ]);
    }

    public function show(TuArsipDokumen $tuArsipDokumen): View
    {
        $tuArsipDokumen->load('archiver');

        return view('kepegawaian-tu.arsip-digital.show', [
            'tuArsipDokumen' => $tuArsipDokumen,
            'tagsInput' => implode(', ', $tuArsipDokumen->tags ?? []),
            'isPrivilegedUser' => $this->isPrivilegedUser(),
        ]);
    }

    public function update(
        UpdateTuArsipDokumenRequest $request,
        TuArsipDokumen $tuArsipDokumen
    ): RedirectResponse {
        $before = $tuArsipDokumen->toArray();

        $validated = $request->validated();
        $tags = $this->parseTags((string) ($validated['tags'] ?? ''));
        $retensiSampai = $validated['retensi_sampai'] ?? null;

        $metadata = $tuArsipDokumen->metadata ?? [];
        $catatanVersi = trim((string) ($validated['catatan_versi'] ?? ''));

        if ($catatanVersi !== '') {
            $riwayatCatatan = $metadata['riwayat_catatan_versi'] ?? [];
            if (! is_array($riwayatCatatan)) {
                $riwayatCatatan = [];
            }

            $riwayatCatatan[] = [
                'catatan' => $catatanVersi,
                'oleh' => auth()->user()?->name,
                'waktu' => now()->toDateTimeString(),
            ];

            $metadata['riwayat_catatan_versi'] = collect($riwayatCatatan)->take(-20)->values()->all();
            $metadata['catatan_versi_terakhir'] = $catatanVersi;
        }

        $tuArsipDokumen->fill([
            'tags' => $tags,
            'retensi_sampai' => $retensiSampai,
            'metadata' => $metadata,
        ]);

        if (! $tuArsipDokumen->isDirty()) {
            return redirect()
                ->route('kepegawaian-tu.arsip-digital.show', $tuArsipDokumen)
                ->with('info', 'Tidak ada perubahan data arsip.');
        }

        $tuArsipDokumen->version = (int) $tuArsipDokumen->version + 1;
        $tuArsipDokumen->save();

        AuditLog::record('tu-arsip-digital', 'update', $tuArsipDokumen, $before, $tuArsipDokumen->toArray());

        return redirect()
            ->route('kepegawaian-tu.arsip-digital.show', $tuArsipDokumen)
            ->with('success', 'Metadata arsip berhasil diperbarui.');
    }

    /**
     * @return array<int, string>
     */
    private function parseTags(string $tagsRaw): array
    {
        if ($tagsRaw === '') {
            return [];
        }

        return collect(explode(',', $tagsRaw))
            ->map(fn (string $tag): string => trim($tag))
            ->filter(fn (string $tag): bool => $tag !== '')
            ->map(fn (string $tag): string => strtolower($tag))
            ->unique()
            ->values()
            ->all();
    }

    private function isPrivilegedUser(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'pejabat'], true);
    }
}
