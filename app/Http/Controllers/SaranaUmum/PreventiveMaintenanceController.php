<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaranaUmum\StorePreventiveMaintenanceRequest;
use App\Http\Requests\SaranaUmum\UpdatePreventiveMaintenanceRequest;
use App\Models\AuditLog;
use App\Models\SaranaUmum;
use App\Models\SaranaUmumPreventiveMaintenance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PreventiveMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $preventives = SaranaUmumPreventiveMaintenance::query()
            ->with('saranaUmum')
            ->when($request->status === 'aktif', fn ($query) => $query->where('is_active', true))
            ->when($request->status === 'nonaktif', fn ($query) => $query->where('is_active', false))
            ->orderBy('tanggal_maintenance_berikutnya')
            ->paginate(12)
            ->withQueryString();

        $summary = [
            'total' => SaranaUmumPreventiveMaintenance::query()->count(),
            'aktif' => SaranaUmumPreventiveMaintenance::query()->where('is_active', true)->count(),
            'jatuh_tempo_7_hari' => SaranaUmumPreventiveMaintenance::query()
                ->where('is_active', true)
                ->whereBetween('tanggal_maintenance_berikutnya', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->count(),
            'overdue' => SaranaUmumPreventiveMaintenance::query()
                ->where('is_active', true)
                ->whereDate('tanggal_maintenance_berikutnya', '<', now()->toDateString())
                ->count(),
        ];

        return view('sarana-umum.preventive.index', compact('preventives', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $saranaUmums = SaranaUmum::query()->orderBy('nama')->get();

        return view('sarana-umum.preventive.create', compact('saranaUmums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePreventiveMaintenanceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);

        $preventive = SaranaUmumPreventiveMaintenance::query()->create($data);

        AuditLog::record('preventive-maintenance', 'create', $preventive, null, $preventive->toArray());

        return redirect()
            ->route('sarana-umum.preventive-maintenance.index')
            ->with('success', 'Jadwal preventive maintenance berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaranaUmumPreventiveMaintenance $preventiveMaintenance): View
    {
        $preventiveMaintenance->load('saranaUmum');

        return view('sarana-umum.preventive.show', compact('preventiveMaintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaranaUmumPreventiveMaintenance $preventiveMaintenance): View
    {
        $saranaUmums = SaranaUmum::query()->orderBy('nama')->get();

        return view('sarana-umum.preventive.edit', compact('preventiveMaintenance', 'saranaUmums'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePreventiveMaintenanceRequest $request, SaranaUmumPreventiveMaintenance $preventiveMaintenance): RedirectResponse
    {
        $before = $preventiveMaintenance->toArray();
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', false);

        $preventiveMaintenance->update($data);

        AuditLog::record('preventive-maintenance', 'update', $preventiveMaintenance, $before, $preventiveMaintenance->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.preventive-maintenance.index')
            ->with('success', 'Jadwal preventive maintenance berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaranaUmumPreventiveMaintenance $preventiveMaintenance): RedirectResponse
    {
        $before = $preventiveMaintenance->toArray();
        $preventiveMaintenance->delete();

        AuditLog::record('preventive-maintenance', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.preventive-maintenance.index')
            ->with('success', 'Jadwal preventive maintenance berhasil dihapus.');
    }

    public function complete(Request $request, SaranaUmumPreventiveMaintenance $preventiveMaintenance): RedirectResponse
    {
        $completionDate = $request->input('tanggal_selesai')
            ? Carbon::parse($request->input('tanggal_selesai'))->startOfDay()
            : now()->startOfDay();

        $before = $preventiveMaintenance->toArray();

        $preventiveMaintenance->update([
            'tanggal_maintenance_terakhir' => $completionDate,
            'tanggal_maintenance_berikutnya' => $completionDate->copy()->addDays($preventiveMaintenance->interval_hari),
        ]);

        AuditLog::record('preventive-maintenance', 'complete', $preventiveMaintenance, $before, $preventiveMaintenance->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.preventive-maintenance.index')
            ->with('success', 'Preventive maintenance ditandai selesai.');
    }
}
