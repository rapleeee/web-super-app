<?php

namespace App\Http\Controllers;

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
     * Export weekly report as CSV.
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

        $logs = MaintenanceLog::with([
            'komponenPerangkat.kategori',
            'komponenPerangkat.unitKomputer.laboratorium',
        ])
            ->whereBetween('tanggal_lapor', [$startDate, $endDate])
            ->orderBy('tanggal_lapor', 'asc')
            ->get();

        // Summary counts
        $totalMasuk = $logs->count();
        $totalSelesai = $logs->where('status', 'selesai')->count();
        $totalPending = $logs->where('status', 'pending')->count();
        $totalProses = $logs->where('status', 'proses')->count();

        // Build CSV content
        $csvContent = [];

        // Header info
        $csvContent[] = ['LAPORAN MINGGUAN MAINTENANCE LABORATORIUM'];
        $csvContent[] = ['Periode: ' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')];
        $csvContent[] = ['Tanggal Cetak: ' . now()->format('d M Y H:i')];
        $csvContent[] = [];

        // Summary
        $csvContent[] = ['RINGKASAN'];
        $csvContent[] = ['Total Laporan Masuk', $totalMasuk];
        $csvContent[] = ['Total Selesai', $totalSelesai];
        $csvContent[] = ['Total Dalam Proses', $totalProses];
        $csvContent[] = ['Total Pending', $totalPending];
        $csvContent[] = [];

        // Detail header
        $csvContent[] = ['DETAIL LAPORAN'];
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
            'Tanggal Selesai',
        ];

        // Detail rows
        foreach ($logs as $index => $log) {
            $csvContent[] = [
                $index + 1,
                $log->tanggal_lapor?->format('d/m/Y') ?? '-',
                $log->komponenPerangkat?->unitKomputer?->laboratorium?->nama ?? '-',
                $log->komponenPerangkat?->unitKomputer?->nama ?? '-',
                ($log->komponenPerangkat?->kategori?->nama ?? '-') . ' (' . ($log->komponenPerangkat?->kode_inventaris ?? '-') . ')',
                $log->keluhan ?? '-',
                ucfirst($log->status),
                $log->kondisi_sebelum ? ucfirst(str_replace('_', ' ', $log->kondisi_sebelum)) : '-',
                $log->kondisi_sesudah ? ucfirst(str_replace('_', ' ', $log->kondisi_sesudah)) : '-',
                $log->tindakan ?? '-',
                $log->tanggal_selesai?->format('d/m/Y') ?? '-',
            ];
        }

        // Convert to CSV string
        $output = fopen('php://temp', 'r+');
        foreach ($csvContent as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        $filename = 'laporan_maintenance_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
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
