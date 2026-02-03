<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreLaboranRequest;
use App\Http\Requests\Laboran\UpdateLaboranRequest;
use App\Models\Laboran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaboranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $petugasLaboran = Laboran::query()
            ->latest()
            ->paginate(10);

        return view('laboran.petugas.index', compact('petugasLaboran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('laboran.petugas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboranRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laboran', 'public');
        }

        Laboran::create($data);

        return redirect()
            ->route('laboran.petugas.index')
            ->with('success', 'Data petugas laboran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboran $petugas): View
    {
        $petugas->load('laboratoriums');

        return view('laboran.petugas.show', compact('petugas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboran $petugas): View
    {
        return view('laboran.petugas.edit', compact('petugas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboranRequest $request, Laboran $petugas): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($petugas->foto) {
                Storage::disk('public')->delete($petugas->foto);
            }
            $data['foto'] = $request->file('foto')->store('laboran', 'public');
        }

        $petugas->update($data);

        return redirect()
            ->route('laboran.petugas.index')
            ->with('success', 'Data petugas laboran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboran $petugas): RedirectResponse
    {
        if ($petugas->foto) {
            Storage::disk('public')->delete($petugas->foto);
        }

        $petugas->delete();

        return redirect()
            ->route('laboran.petugas.index')
            ->with('success', 'Data petugas laboran berhasil dihapus.');
    }
}
