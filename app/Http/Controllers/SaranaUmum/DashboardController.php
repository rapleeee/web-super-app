<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Models\SaranaUmum;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Sarana Umum dashboard.
     */
    public function index(): View
    {
        $totalSarana = SaranaUmum::query()->count();

        $saranaBermasalah = SaranaUmum::query()
            ->whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'mati_total'])
            ->count();

        $dalamPerbaikan = SaranaUmum::query()
            ->where('status', 'dalam_perbaikan')
            ->count();

        $kondisiStats = [
            'baik' => SaranaUmum::query()->where('kondisi', 'baik')->count(),
            'rusak_ringan' => SaranaUmum::query()->where('kondisi', 'rusak_ringan')->count(),
            'rusak_berat' => SaranaUmum::query()->where('kondisi', 'rusak_berat')->count(),
            'mati_total' => SaranaUmum::query()->where('kondisi', 'mati_total')->count(),
        ];

        $statusStats = [
            'aktif' => SaranaUmum::query()->where('status', 'aktif')->count(),
            'dalam_perbaikan' => SaranaUmum::query()->where('status', 'dalam_perbaikan')->count(),
            'tidak_aktif' => SaranaUmum::query()->where('status', 'tidak_aktif')->count(),
        ];

        $saranaTerbaru = SaranaUmum::query()
            ->latest()
            ->limit(6)
            ->get();

        return view('sarana-umum.dashboard', compact(
            'totalSarana',
            'saranaBermasalah',
            'dalamPerbaikan',
            'kondisiStats',
            'statusStats',
            'saranaTerbaru'
        ));
    }
}
