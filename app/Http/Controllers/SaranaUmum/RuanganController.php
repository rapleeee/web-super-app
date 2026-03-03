<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreLaboratoriumRequest;
use App\Http\Requests\Laboran\UpdateLaboratoriumRequest;
use App\Models\AuditLog;
use App\Models\Laboran;
use App\Models\Laboratorium;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $ruangans = Laboratorium::query()
            ->with('penanggungJawab')
            ->latest()
            ->paginate(10);

        return view('sarana-umum.data-ruangan.index', compact('ruangans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $petugasLaboran = Laboran::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('sarana-umum.data-ruangan.create', compact('petugasLaboran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboratoriumRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('ruangan-sarana-umum', 'public');
        }

        $ruangan = Laboratorium::query()->create($data);
        AuditLog::record('data-ruangan', 'create', $ruangan, null, $ruangan->toArray());

        return redirect()
            ->route('sarana-umum.data-ruangan.index')
            ->with('success', 'Data ruangan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratorium $laboratorium): View
    {
        $laboratorium->load('penanggungJawab');

        return view('sarana-umum.data-ruangan.show', ['ruangan' => $laboratorium]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboratorium $laboratorium): View
    {
        $petugasLaboran = Laboran::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('sarana-umum.data-ruangan.edit', [
            'ruangan' => $laboratorium,
            'petugasLaboran' => $petugasLaboran,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboratoriumRequest $request, Laboratorium $laboratorium): RedirectResponse
    {
        $before = $laboratorium->toArray();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($laboratorium->foto) {
                Storage::disk('public')->delete($laboratorium->foto);
            }

            $data['foto'] = $request->file('foto')->store('ruangan-sarana-umum', 'public');
        }

        $laboratorium->update($data);
        AuditLog::record('data-ruangan', 'update', $laboratorium, $before, $laboratorium->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.data-ruangan.index')
            ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratorium $laboratorium): RedirectResponse
    {
        $before = $laboratorium->toArray();
        if ($laboratorium->foto) {
            Storage::disk('public')->delete($laboratorium->foto);
        }

        $laboratorium->delete();
        AuditLog::record('data-ruangan', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.data-ruangan.index')
            ->with('success', 'Data ruangan berhasil dihapus.');
    }
}
