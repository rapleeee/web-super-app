<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreMaintenanceLogRequest;
use App\Http\Requests\Laboran\UpdateMaintenanceLogRequest;
use App\Models\KomponenPerangkat;
use App\Models\MaintenanceLog;
use App\Models\Notification;
use App\Models\UnitKomputer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $maintenanceLogs = MaintenanceLog::query()
            ->with(['komponenPerangkat.unitKomputer.laboratorium', 'komponenPerangkat.kategori', 'pelapor'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->komponen_id, fn ($q, $id) => $q->where('komponen_perangkat_id', $id))
            ->latest('tanggal_lapor')
            ->paginate(10);

        $stats = [
            'pending' => MaintenanceLog::where('status', 'pending')->count(),
            'proses' => MaintenanceLog::where('status', 'proses')->count(),
            'selesai' => MaintenanceLog::where('status', 'selesai')->count(),
            'tidak_bisa_diperbaiki' => MaintenanceLog::where('status', 'tidak_bisa_diperbaiki')->count(),
        ];

        return view('laboran.perangkat.maintenance.index', compact('maintenanceLogs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $units = UnitKomputer::with('laboratorium')->get();

        // Pre-select unit and komponen if coming from unit show page
        $selectedUnit = $request->unit_id ? UnitKomputer::find($request->unit_id) : null;
        $komponens = $selectedUnit
            ? KomponenPerangkat::where('unit_komputer_id', $selectedUnit->id)->with('kategori')->get()
            : collect();

        return view('laboran.perangkat.maintenance.create', compact('units', 'komponens', 'selectedUnit'));
    }

    /**
     * Get komponens by unit (API for AJAX).
     */
    public function getKomponensByUnit(UnitKomputer $unitKomputer): JsonResponse
    {
        $komponens = KomponenPerangkat::where('unit_komputer_id', $unitKomputer->id)
            ->with('kategori')
            ->get()
            ->map(fn ($k) => [
                'id' => $k->id,
                'label' => $k->kategori->nama.' - '.$k->kode_inventaris,
                'kondisi' => $k->kondisi,
            ]);

        return response()->json($komponens);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaintenanceLogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['pelapor_id'] = auth()->id();

        $log = MaintenanceLog::create($data);
        $log->load(['komponenPerangkat.unitKomputer.laboratorium', 'komponenPerangkat.kategori']);

        // Update kondisi komponen
        $komponen = KomponenPerangkat::find($data['komponen_perangkat_id']);
        if ($komponen) {
            $komponen->update([
                'kondisi' => $data['kondisi_sebelum'],
                'status' => $data['status'] === 'selesai' ? 'aktif' : 'dalam_perbaikan',
            ]);
        }

        // Send notification to all laboran/admin users
        $this->notifyUsers('maintenance_created', $log);

        return redirect()
            ->route('laboran.maintenance-log.index')
            ->with('success', 'Laporan maintenance berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceLog $maintenanceLog): View
    {
        $maintenanceLog->load(['komponenPerangkat.unitKomputer.laboratorium', 'komponenPerangkat.kategori', 'pelapor']);

        return view('laboran.perangkat.maintenance.show', compact('maintenanceLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceLog $maintenanceLog): View
    {
        $komponenPerangkats = KomponenPerangkat::with(['unitKomputer.laboratorium', 'kategori'])->get();

        return view('laboran.perangkat.maintenance.edit', compact('maintenanceLog', 'komponenPerangkats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaintenanceLogRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $data = $request->validated();
        $oldStatus = $maintenanceLog->status;

        $maintenanceLog->update($data);
        $maintenanceLog->load(['komponenPerangkat.unitKomputer.laboratorium', 'komponenPerangkat.kategori']);

        // Update kondisi komponen jika selesai
        if ($data['status'] === 'selesai' && $data['kondisi_sesudah']) {
            $maintenanceLog->komponenPerangkat->update([
                'kondisi' => $data['kondisi_sesudah'],
                'status' => 'aktif',
            ]);
        } elseif ($data['status'] === 'tidak_bisa_diperbaiki') {
            $maintenanceLog->komponenPerangkat->update([
                'status' => 'tidak_aktif',
            ]);
        }

        // Send notification if status changed
        if ($oldStatus !== $data['status']) {
            $this->notifyUsers('maintenance_status_updated', $maintenanceLog, $oldStatus);
        }

        return redirect()
            ->route('laboran.maintenance-log.index')
            ->with('success', 'Laporan maintenance berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $maintenanceLog->delete();

        return redirect()
            ->route('laboran.maintenance-log.index')
            ->with('success', 'Laporan maintenance berhasil dihapus.');
    }

    /**
     * Notify relevant users about maintenance log events.
     */
    private function notifyUsers(string $type, MaintenanceLog $log, ?string $oldStatus = null): void
    {
        // Notify all laboran and admin users except the current user
        $users = User::whereIn('role', ['admin', 'laboran'])
            ->where('id', '!=', auth()->id())
            ->get();

        foreach ($users as $user) {
            if ($type === 'maintenance_created') {
                Notification::maintenanceCreated($log, $user->id);
            } elseif ($type === 'maintenance_status_updated' && $oldStatus) {
                Notification::maintenanceStatusUpdated($log, $oldStatus, $user->id);
            }
        }
    }
}
