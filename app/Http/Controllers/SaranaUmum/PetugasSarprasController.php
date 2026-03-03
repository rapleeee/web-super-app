<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreLaboranRequest;
use App\Http\Requests\Laboran\UpdateLaboranRequest;
use App\Models\AuditLog;
use App\Models\Laboran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PetugasSarprasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $petugasLaboran = Laboran::query()
            ->latest()
            ->paginate(10);

        return view('sarana-umum.petugas.index', compact('petugasLaboran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('sarana-umum.petugas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboranRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('petugas-sarpras', 'public');
        }

        $petugas = Laboran::query()->create($data);
        AuditLog::record('petugas-sarpras', 'create', $petugas, null, $petugas->toArray());

        return redirect()
            ->route('sarana-umum.petugas-sarpras.index')
            ->with('success', 'Data petugas sarpras berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboran $petugas): View
    {
        $petugas->load('laboratoriums');

        return view('sarana-umum.petugas.show', compact('petugas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboran $petugas): View
    {
        return view('sarana-umum.petugas.edit', compact('petugas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboranRequest $request, Laboran $petugas): RedirectResponse
    {
        $before = $petugas->toArray();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($petugas->foto) {
                Storage::disk('public')->delete($petugas->foto);
            }

            $data['foto'] = $request->file('foto')->store('petugas-sarpras', 'public');
        }

        $petugas->update($data);
        AuditLog::record('petugas-sarpras', 'update', $petugas, $before, $petugas->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.petugas-sarpras.index')
            ->with('success', 'Data petugas sarpras berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboran $petugas): RedirectResponse
    {
        $before = $petugas->toArray();
        if ($petugas->foto) {
            Storage::disk('public')->delete($petugas->foto);
        }

        $petugas->delete();
        AuditLog::record('petugas-sarpras', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.petugas-sarpras.index')
            ->with('success', 'Data petugas sarpras berhasil dihapus.');
    }
}
