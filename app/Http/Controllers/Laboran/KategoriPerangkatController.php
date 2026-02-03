<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreKategoriPerangkatRequest;
use App\Http\Requests\Laboran\UpdateKategoriPerangkatRequest;
use App\Models\KategoriPerangkat;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriPerangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $kategoriPerangkats = KategoriPerangkat::query()
            ->withCount('komponenPerangkats')
            ->latest()
            ->paginate(10);

        return view('laboran.perangkat.kategori.index', compact('kategoriPerangkats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('laboran.perangkat.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriPerangkatRequest $request): RedirectResponse
    {
        KategoriPerangkat::create($request->validated());

        return redirect()
            ->route('laboran.kategori-perangkat.index')
            ->with('success', 'Kategori perangkat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriPerangkat $kategoriPerangkat): View
    {
        $kategoriPerangkat->load(['komponenPerangkats.unitKomputer.laboratorium']);

        return view('laboran.perangkat.kategori.show', compact('kategoriPerangkat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriPerangkat $kategoriPerangkat): View
    {
        return view('laboran.perangkat.kategori.edit', compact('kategoriPerangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriPerangkatRequest $request, KategoriPerangkat $kategoriPerangkat): RedirectResponse
    {
        $kategoriPerangkat->update($request->validated());

        return redirect()
            ->route('laboran.kategori-perangkat.index')
            ->with('success', 'Kategori perangkat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPerangkat $kategoriPerangkat): RedirectResponse
    {
        $kategoriPerangkat->delete();

        return redirect()
            ->route('laboran.kategori-perangkat.index')
            ->with('success', 'Kategori perangkat berhasil dihapus.');
    }
}
