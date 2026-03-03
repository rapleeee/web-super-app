<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaranaUmum\StoreSaranaUmumMaintenanceLogRequest;
use App\Http\Requests\SaranaUmum\UpdateSaranaUmumMaintenanceLogRequest;
use App\Models\AuditLog;
use App\Models\SaranaUmum;
use App\Models\SaranaUmumMaintenanceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $maintenanceLogs = SaranaUmumMaintenanceLog::query()
            ->with(['saranaUmum', 'pelapor'])
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest('tanggal_lapor')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'pending' => SaranaUmumMaintenanceLog::query()->where('status', 'pending')->count(),
            'proses' => SaranaUmumMaintenanceLog::query()->where('status', 'proses')->count(),
            'selesai' => SaranaUmumMaintenanceLog::query()->where('status', 'selesai')->count(),
            'tidak_bisa_diperbaiki' => SaranaUmumMaintenanceLog::query()->where('status', 'tidak_bisa_diperbaiki')->count(),
            'sla_breached' => SaranaUmumMaintenanceLog::query()
                ->whereIn('status', ['pending', 'proses'])
                ->whereDate('sla_deadline', '<', now()->toDateString())
                ->count(),
            'sla_due_today' => SaranaUmumMaintenanceLog::query()
                ->whereIn('status', ['pending', 'proses'])
                ->whereDate('sla_deadline', now()->toDateString())
                ->count(),
            'total_biaya' => (float) SaranaUmumMaintenanceLog::query()->sum('biaya'),
            'total_biaya_bulan_ini' => (float) SaranaUmumMaintenanceLog::query()
                ->whereMonth('tanggal_lapor', now()->month)
                ->whereYear('tanggal_lapor', now()->year)
                ->sum('biaya'),
        ];

        return view('sarana-umum.maintenance-log.index', compact('maintenanceLogs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $saranaUmums = SaranaUmum::query()
            ->orderBy('nama')
            ->get();

        return view('sarana-umum.maintenance-log.create', compact('saranaUmums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaranaUmumMaintenanceLogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['pelapor_id'] = auth()->id();
        $data['sla_deadline'] = $data['sla_deadline'] ?? Carbon::parse($data['tanggal_lapor'])->addDays(3)->toDateString();

        foreach (['bukti_sebelum', 'bukti_sesudah', 'bukti_invoice'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('sarana-umum/maintenance-bukti', 'public');
            }
        }

        $maintenanceLog = SaranaUmumMaintenanceLog::query()->create($data);
        $this->syncSaranaCondition($maintenanceLog);

        AuditLog::record('maintenance-log', 'create', $maintenanceLog, null, $maintenanceLog->toArray());

        return redirect()
            ->route('sarana-umum.maintenance-log.index')
            ->with('success', 'Laporan maintenance sarana umum berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaranaUmumMaintenanceLog $maintenanceLog): View
    {
        $maintenanceLog->load(['saranaUmum', 'pelapor']);

        return view('sarana-umum.maintenance-log.show', compact('maintenanceLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaranaUmumMaintenanceLog $maintenanceLog): View
    {
        $maintenanceLog->load(['saranaUmum', 'pelapor']);

        $saranaUmums = SaranaUmum::query()
            ->orderBy('nama')
            ->get();

        return view('sarana-umum.maintenance-log.edit', compact('maintenanceLog', 'saranaUmums'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaranaUmumMaintenanceLogRequest $request, SaranaUmumMaintenanceLog $maintenanceLog): RedirectResponse
    {
        $before = $maintenanceLog->toArray();
        $data = $request->validated();

        foreach (['bukti_sebelum', 'bukti_sesudah', 'bukti_invoice'] as $field) {
            if ($request->hasFile($field)) {
                if ($maintenanceLog->{$field}) {
                    Storage::disk('public')->delete($maintenanceLog->{$field});
                }

                $data[$field] = $request->file($field)->store('sarana-umum/maintenance-bukti', 'public');
            }
        }

        $maintenanceLog->update($data);
        $maintenanceLog->refresh();

        $this->syncSaranaCondition($maintenanceLog);
        AuditLog::record('maintenance-log', 'update', $maintenanceLog, $before, $maintenanceLog->toArray());

        return redirect()
            ->route('sarana-umum.maintenance-log.index')
            ->with('success', 'Laporan maintenance sarana umum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaranaUmumMaintenanceLog $maintenanceLog): RedirectResponse
    {
        $before = $maintenanceLog->toArray();

        foreach (['bukti_sebelum', 'bukti_sesudah', 'bukti_invoice'] as $field) {
            if ($maintenanceLog->{$field}) {
                Storage::disk('public')->delete($maintenanceLog->{$field});
            }
        }

        $maintenanceLog->delete();

        AuditLog::record('maintenance-log', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.maintenance-log.index')
            ->with('success', 'Laporan maintenance sarana umum berhasil dihapus.');
    }

    private function syncSaranaCondition(SaranaUmumMaintenanceLog $maintenanceLog): void
    {
        $sarana = $maintenanceLog->saranaUmum;
        if (! $sarana) {
            return;
        }

        if ($maintenanceLog->status === 'selesai' && $maintenanceLog->kondisi_sesudah) {
            $sarana->update([
                'kondisi' => $maintenanceLog->kondisi_sesudah,
                'status' => 'aktif',
            ]);

            return;
        }

        if ($maintenanceLog->status === 'tidak_bisa_diperbaiki') {
            $sarana->update([
                'status' => 'tidak_aktif',
            ]);

            return;
        }

        $sarana->update([
            'kondisi' => $maintenanceLog->kondisi_sebelum,
            'status' => 'dalam_perbaikan',
        ]);
    }
}
