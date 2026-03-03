<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use App\Models\IzinKaryawan;
use App\Models\SaranaUmumBeritaAcara;
use App\Models\TuArsipDokumen;
use App\Models\TuSurat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $slaHari = max((int) config('kepegawaian_tu.izin.sla_hari', 3), 1);

        $izinPending = IzinKaryawan::query()->where('status', 'diajukan')->count();
        $izinPendingLebih3Hari = IzinKaryawan::query()
            ->where('status', 'diajukan')
            ->whereDate('created_at', '<=', now()->subDays($slaHari)->toDateString())
            ->count();
        $izinDiajukanHariIni = IzinKaryawan::query()
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $izinDisetujuiBulanIni = IzinKaryawan::query()
            ->where('status', 'disetujui')
            ->whereMonth('approved_at', now()->month)
            ->whereYear('approved_at', now()->year)
            ->count();
        $izinDitolakBulanIni = IzinKaryawan::query()
            ->where('status', 'ditolak')
            ->whereMonth('approved_at', now()->month)
            ->whereYear('approved_at', now()->year)
            ->count();
        $approvalSelesaiHariIni = IzinKaryawan::query()
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereDate('approved_at', now()->toDateString())
            ->count();

        $beritaAcaraFinalLaboran = BeritaAcara::query()->where('status', 'final')->count();
        $beritaAcaraFinalSaranaUmum = SaranaUmumBeritaAcara::query()->where('status', 'final')->count();
        $dokumenFinalMasukHariIni = BeritaAcara::query()
            ->where('status', 'final')
            ->whereDate('created_at', now()->toDateString())
            ->count() + SaranaUmumBeritaAcara::query()
            ->where('status', 'final')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $suratDraft = TuSurat::query()->where('status', 'draft')->count();
        $suratReview = TuSurat::query()->where('status', 'review')->count();
        $suratFinal = TuSurat::query()->where('status', 'final')->count();
        $arsipDigital = TuArsipDokumen::query()->count();

        $recentIzin = IzinKaryawan::query()
            ->with(['pemohon', 'approver'])
            ->latest()
            ->limit(6)
            ->get();
        $prioritasIzin = IzinKaryawan::query()
            ->with('pemohon')
            ->where('status', 'diajukan')
            ->oldest('created_at')
            ->limit(5)
            ->get();
        $isPrivilegedUser = in_array(auth()->user()?->role, ['admin', 'pejabat'], true);

        return view('kepegawaian-tu.dashboard', [
            'slaHari' => $slaHari,
            'izinPending' => $izinPending,
            'izinPendingLebih3Hari' => $izinPendingLebih3Hari,
            'izinDiajukanHariIni' => $izinDiajukanHariIni,
            'izinDisetujuiBulanIni' => $izinDisetujuiBulanIni,
            'izinDitolakBulanIni' => $izinDitolakBulanIni,
            'approvalSelesaiHariIni' => $approvalSelesaiHariIni,
            'totalBeritaAcaraFinal' => $beritaAcaraFinalLaboran + $beritaAcaraFinalSaranaUmum,
            'dokumenFinalMasukHariIni' => $dokumenFinalMasukHariIni,
            'suratDraft' => $suratDraft,
            'suratReview' => $suratReview,
            'suratFinal' => $suratFinal,
            'arsipDigital' => $arsipDigital,
            'recentIzin' => $recentIzin,
            'prioritasIzin' => $prioritasIzin,
            'isPrivilegedUser' => $isPrivilegedUser,
        ]);
    }
}
