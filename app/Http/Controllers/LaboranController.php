<?php

namespace App\Http\Controllers;

use App\Models\KomponenPerangkat;
use App\Models\Laboratorium;
use App\Models\MaintenanceLog;
use App\Models\UnitKomputer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LaboranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Key Metrics
        $unitAktif = UnitKomputer::where('kondisi', 'baik')->count();
        $unitBermasalah = UnitKomputer::whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'mati_total'])->count();
        $selesaiBulanIni = MaintenanceLog::where('status', 'selesai')
            ->whereMonth('tanggal_selesai', now()->month)
            ->whereYear('tanggal_selesai', now()->year)
            ->count();

        // Pending logs for work area
        $pendingLogs = MaintenanceLog::with(['komponenPerangkat.kategori', 'komponenPerangkat.unitKomputer.laboratorium'])
            ->where('status', 'pending')
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        // In-progress logs for work area
        $prosesLogs = MaintenanceLog::with(['komponenPerangkat.kategori', 'komponenPerangkat.unitKomputer.laboratorium'])
            ->where('status', 'proses')
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        // Unit kondisi for chart
        $unitKondisi = [
            'baik' => UnitKomputer::where('kondisi', 'baik')->count(),
            'rusak_ringan' => UnitKomputer::where('kondisi', 'rusak_ringan')->count(),
            'rusak_berat' => UnitKomputer::where('kondisi', 'rusak_berat')->count(),
            'mati_total' => UnitKomputer::where('kondisi', 'mati_total')->count(),
        ];

        // Stats per lab (simplified)
        $labStats = Laboratorium::withCount(['unitKomputers as total_units'])
            ->withCount(['unitKomputers as bermasalah' => function ($query) {
                $query->whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'mati_total']);
            }])
            ->get();

        return view('laboran.index', compact(
            'unitAktif',
            'unitBermasalah',
            'selesaiBulanIni',
            'pendingLogs',
            'prosesLogs',
            'unitKondisi',
            'labStats'
        ));
    }

    /**
     * Export weekly report as CSV (Inventory + Maintenance).
     */
    public function exportWeeklyReport(Request $request): Response
    {
        // Determine date range (defaults to current week, Mon-Sun)
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : now()->startOfWeek(Carbon::MONDAY);
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : now()->endOfWeek(Carbon::SUNDAY);

        // Build CSV content
        $csvContent = [];

        // ========== HEADER INFO ==========
        $csvContent[] = ['LAPORAN LABORATORIUM KOMPUTER'];
        $csvContent[] = ['Periode: '.$startDate->format('d M Y').' - '.$endDate->format('d M Y')];
        $csvContent[] = ['Tanggal Cetak: '.now()->format('d M Y H:i')];
        $csvContent[] = [];

        // ========== SECTION 1: RINGKASAN GLOBAL ==========
        $csvContent[] = ['=== RINGKASAN GLOBAL ==='];
        $csvContent[] = [];

        $totalLab = Laboratorium::count();
        $totalUnit = UnitKomputer::count();
        $totalKomponen = KomponenPerangkat::count();

        $unitByKondisi = [
            'Baik' => UnitKomputer::where('kondisi', 'baik')->count(),
            'Rusak Ringan' => UnitKomputer::where('kondisi', 'rusak_ringan')->count(),
            'Rusak Berat' => UnitKomputer::where('kondisi', 'rusak_berat')->count(),
            'Mati Total' => UnitKomputer::where('kondisi', 'mati_total')->count(),
        ];

        $komponenByKondisi = [
            'Baik' => KomponenPerangkat::where('kondisi', 'baik')->count(),
            'Rusak Ringan' => KomponenPerangkat::where('kondisi', 'rusak_ringan')->count(),
            'Rusak Berat' => KomponenPerangkat::where('kondisi', 'rusak_berat')->count(),
            'Mati Total' => KomponenPerangkat::where('kondisi', 'mati_total')->count(),
        ];

        $csvContent[] = ['Total Laboratorium', $totalLab];
        $csvContent[] = ['Total Unit Komputer', $totalUnit];
        $csvContent[] = ['Total Komponen', $totalKomponen];
        $csvContent[] = [];

        $csvContent[] = ['Kondisi Unit Komputer:'];
        foreach ($unitByKondisi as $kondisi => $count) {
            $csvContent[] = ['  - '.$kondisi, $count];
        }
        $csvContent[] = [];

        $csvContent[] = ['Kondisi Komponen:'];
        foreach ($komponenByKondisi as $kondisi => $count) {
            $csvContent[] = ['  - '.$kondisi, $count];
        }
        $csvContent[] = [];

        // ========== SECTION 2: INVENTORY PER LABORATORIUM ==========
        $csvContent[] = ['=== INVENTORY PER LABORATORIUM ==='];
        $csvContent[] = [];

        $labs = Laboratorium::with([
            'unitKomputers.komponenPerangkats.kategori',
            'penanggungJawab',
        ])->get();

        foreach ($labs as $lab) {
            $csvContent[] = ['LABORATORIUM: '.$lab->nama];
            $csvContent[] = ['Kode', $lab->kode];
            $csvContent[] = ['Lokasi', $lab->lokasi ?? '-'];
            $csvContent[] = ['Kapasitas', $lab->kapasitas.' siswa'];
            $csvContent[] = ['Status', ucfirst($lab->status)];
            $csvContent[] = ['Penanggung Jawab', $lab->penanggungJawab?->nama ?? '-'];
            $csvContent[] = [];

            // Stats per lab
            $labUnits = $lab->unitKomputers;
            $labKomponens = $labUnits->flatMap->komponenPerangkats;

            $csvContent[] = ['Jumlah Unit Komputer', $labUnits->count()];
            $csvContent[] = ['  - Kondisi Baik', $labUnits->where('kondisi', 'baik')->count()];
            $csvContent[] = ['  - Rusak Ringan', $labUnits->where('kondisi', 'rusak_ringan')->count()];
            $csvContent[] = ['  - Rusak Berat', $labUnits->where('kondisi', 'rusak_berat')->count()];
            $csvContent[] = ['  - Mati Total', $labUnits->where('kondisi', 'mati_total')->count()];
            $csvContent[] = [];

            $csvContent[] = ['Jumlah Komponen', $labKomponens->count()];
            $csvContent[] = ['  - Kondisi Baik', $labKomponens->where('kondisi', 'baik')->count()];
            $csvContent[] = ['  - Rusak Ringan', $labKomponens->where('kondisi', 'rusak_ringan')->count()];
            $csvContent[] = ['  - Rusak Berat', $labKomponens->where('kondisi', 'rusak_berat')->count()];
            $csvContent[] = ['  - Mati Total', $labKomponens->where('kondisi', 'mati_total')->count()];
            $csvContent[] = [];

            // List komponen by category
            $komponenByKategori = $labKomponens->groupBy(fn ($k) => $k->kategori?->nama ?? 'Lainnya');
            if ($komponenByKategori->isNotEmpty()) {
                $csvContent[] = ['Komponen per Kategori:'];
                foreach ($komponenByKategori as $kategori => $items) {
                    $baik = $items->where('kondisi', 'baik')->count();
                    $bermasalah = $items->whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'mati_total'])->count();
                    $csvContent[] = ['  - '.$kategori, 'Total: '.$items->count(), 'Baik: '.$baik, 'Bermasalah: '.$bermasalah];
                }
            }

            $csvContent[] = [];
            $csvContent[] = ['---'];
            $csvContent[] = [];
        }

        // ========== SECTION 3: DETAIL UNIT KOMPUTER ==========
        $csvContent[] = ['=== DETAIL UNIT KOMPUTER ==='];
        $csvContent[] = [];

        $csvContent[] = [
            'No',
            'Laboratorium',
            'Nama Unit',
            'Kode Inventaris',
            'Merk/Model',
            'Tahun Pengadaan',
            'Kondisi',
            'Status',
            'Jumlah Komponen',
        ];

        $allUnits = UnitKomputer::with(['laboratorium', 'komponenPerangkats'])->get();
        foreach ($allUnits as $index => $unit) {
            $csvContent[] = [
                $index + 1,
                $unit->laboratorium?->nama ?? '-',
                $unit->nama,
                $unit->kode_inventaris ?? '-',
                trim(($unit->merk ?? '').'/'.($unit->model ?? ''), '/') ?: '-',
                $unit->tahun_pengadaan ?? '-',
                ucfirst(str_replace('_', ' ', $unit->kondisi)),
                ucfirst($unit->status ?? 'aktif'),
                $unit->komponenPerangkats->count(),
            ];
        }
        $csvContent[] = [];

        // ========== SECTION 4: MAINTENANCE LOGS ==========
        $csvContent[] = ['=== LAPORAN MAINTENANCE (PERIODE) ==='];
        $csvContent[] = [];

        $logs = MaintenanceLog::with([
            'komponenPerangkat.kategori',
            'komponenPerangkat.unitKomputer.laboratorium',
            'pelapor',
        ])
            ->whereBetween('tanggal_lapor', [$startDate, $endDate])
            ->orderBy('tanggal_lapor', 'asc')
            ->get();

        // Summary counts
        $totalMasuk = $logs->count();
        $totalSelesai = $logs->where('status', 'selesai')->count();
        $totalPending = $logs->where('status', 'pending')->count();
        $totalProses = $logs->where('status', 'proses')->count();
        $totalTidakBisa = $logs->where('status', 'tidak_bisa_diperbaiki')->count();

        $csvContent[] = ['Ringkasan Maintenance Periode Ini:'];
        $csvContent[] = ['Total Laporan Masuk', $totalMasuk];
        $csvContent[] = ['  - Selesai', $totalSelesai];
        $csvContent[] = ['  - Dalam Proses', $totalProses];
        $csvContent[] = ['  - Pending', $totalPending];
        $csvContent[] = ['  - Tidak Bisa Diperbaiki', $totalTidakBisa];
        $csvContent[] = [];

        if ($logs->isNotEmpty()) {
            $csvContent[] = ['Detail Laporan:'];
            $csvContent[] = [
                'No',
                'Tanggal Lapor',
                'Laboratorium',
                'Unit Komputer',
                'Komponen',
                'Keluhan',
                'Status',
                'Kondisi Sebelum',
                'Kondisi Sesudah',
                'Tindakan',
                'Teknisi',
                'Tanggal Selesai',
            ];

            foreach ($logs as $index => $log) {
                $csvContent[] = [
                    $index + 1,
                    $log->tanggal_lapor?->format('d/m/Y') ?? '-',
                    $log->komponenPerangkat?->unitKomputer?->laboratorium?->nama ?? '-',
                    $log->komponenPerangkat?->unitKomputer?->nama ?? '-',
                    ($log->komponenPerangkat?->kategori?->nama ?? '-').' ('.$log->komponenPerangkat?->kode_inventaris.')',
                    $log->keluhan ?? '-',
                    ucfirst(str_replace('_', ' ', $log->status)),
                    $log->kondisi_sebelum ? ucfirst(str_replace('_', ' ', $log->kondisi_sebelum)) : '-',
                    $log->kondisi_sesudah ? ucfirst(str_replace('_', ' ', $log->kondisi_sesudah)) : '-',
                    $log->tindakan ?? '-',
                    $log->teknisi ?? '-',
                    $log->tanggal_selesai?->format('d/m/Y') ?? '-',
                ];
            }
        } else {
            $csvContent[] = ['Tidak ada laporan maintenance pada periode ini.'];
        }

        // Convert to CSV string
        $output = fopen('php://temp', 'r+');
        foreach ($csvContent as $row) {
            fputcsv($output, $row, ',', '"', '');
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        $filename = 'laporan_laboratorium_'.$startDate->format('Ymd').'_'.$endDate->format('Ymd').'.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
