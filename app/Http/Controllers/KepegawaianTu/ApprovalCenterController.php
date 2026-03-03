<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use App\Models\IzinKaryawan;
use App\Models\SaranaUmumBeritaAcara;
use App\Models\TuSurat;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ApprovalCenterController extends Controller
{
    public function index(): View
    {
        $slaHari = max((int) config('kepegawaian_tu.izin.sla_hari', 3), 1);

        $pendingIzin = IzinKaryawan::query()
            ->with('pemohon')
            ->where('status', 'diajukan')
            ->orderBy('created_at')
            ->paginate(12)
            ->withQueryString();
        $suratReviewQueue = TuSurat::query()
            ->with(['creator', 'template'])
            ->where('status', 'review')
            ->oldest('updated_at')
            ->limit(8)
            ->get();

        $dokumenFinalTerbaru = $this->collectFinalDocumentFeed();

        $kpi = [
            'surat_review' => TuSurat::query()->where('status', 'review')->count(),
            'pending' => IzinKaryawan::query()->where('status', 'diajukan')->count(),
            'overdue_sla' => IzinKaryawan::query()
                ->where('status', 'diajukan')
                ->whereDate('created_at', '<', now()->subDays($slaHari)->toDateString())
                ->count(),
            'approval_hari_ini' => IzinKaryawan::query()
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->whereDate('approved_at', now()->toDateString())
                ->count(),
            'dokumen_final_hari_ini' => $dokumenFinalTerbaru
                ->where(fn (array $row): bool => $row['created_at']->isToday())
                ->count(),
        ];

        return view('kepegawaian-tu.pusat-approval.index', [
            'pendingIzin' => $pendingIzin,
            'suratReviewQueue' => $suratReviewQueue,
            'dokumenFinalTerbaru' => $dokumenFinalTerbaru,
            'kpi' => $kpi,
            'slaHari' => $slaHari,
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function collectFinalDocumentFeed(): Collection
    {
        $laboran = BeritaAcara::query()
            ->with(['laboratorium', 'user'])
            ->where('status', 'final')
            ->latest('created_at')
            ->limit(7)
            ->get()
            ->map(function (BeritaAcara $record): array {
                return [
                    'sumber' => 'Laboran',
                    'created_at' => $record->created_at,
                    'tanggal' => $record->tanggal,
                    'nama_guru' => $record->nama_guru,
                    'lokasi' => $record->laboratorium?->nama ?? '-',
                    'petugas' => $record->user?->name ?? '-',
                    'route_detail' => route('laboran.berita-acara.show', $record),
                ];
            });

        $saranaUmum = SaranaUmumBeritaAcara::query()
            ->with(['ruangan', 'user'])
            ->where('status', 'final')
            ->latest('created_at')
            ->limit(7)
            ->get()
            ->map(function (SaranaUmumBeritaAcara $record): array {
                return [
                    'sumber' => 'Sarana Umum',
                    'created_at' => $record->created_at,
                    'tanggal' => $record->tanggal,
                    'nama_guru' => $record->nama_guru,
                    'lokasi' => $record->ruangan?->nama ?? '-',
                    'petugas' => $record->user?->name ?? '-',
                    'route_detail' => route('sarana-umum.berita-acara.show', $record),
                ];
            });

        return $laboran
            ->concat($saranaUmum)
            ->sortByDesc(fn (array $item): mixed => $item['created_at'])
            ->values()
            ->take(10)
            ->values();
    }
}
