<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreLaboratoriumRequest;
use App\Http\Requests\Laboran\UpdateLaboratoriumRequest;
use App\Models\Laboran;
use App\Models\Laboratorium;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaboratoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $laboratoriums = Laboratorium::query()
            ->with('penanggungJawab')
            ->latest()
            ->paginate(10);

        return view('laboran.laboratorium.index', compact('laboratoriums'));
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

        return view('laboran.laboratorium.create', compact('petugasLaboran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboratoriumRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laboratorium', 'public');
        }

        Laboratorium::create($data);

        return redirect()
            ->route('laboran.laboratorium.index')
            ->with('success', 'Data laboratorium berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratorium $laboratorium): View
    {
        $laboratorium->load('penanggungJawab');

        return view('laboran.laboratorium.show', compact('laboratorium'));
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

        return view('laboran.laboratorium.edit', compact('laboratorium', 'petugasLaboran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboratoriumRequest $request, Laboratorium $laboratorium): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($laboratorium->foto) {
                Storage::disk('public')->delete($laboratorium->foto);
            }
            $data['foto'] = $request->file('foto')->store('laboratorium', 'public');
        }

        $laboratorium->update($data);

        return redirect()
            ->route('laboran.laboratorium.index')
            ->with('success', 'Data laboratorium berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratorium $laboratorium): RedirectResponse
    {
        if ($laboratorium->foto) {
            Storage::disk('public')->delete($laboratorium->foto);
        }

        $laboratorium->delete();

        return redirect()
            ->route('laboran.laboratorium.index')
            ->with('success', 'Data laboratorium berhasil dihapus.');
    }
}
