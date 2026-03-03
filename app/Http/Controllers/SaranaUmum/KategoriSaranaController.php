<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreKategoriPerangkatRequest;
use App\Http\Requests\Laboran\UpdateKategoriPerangkatRequest;
use App\Models\AuditLog;
use App\Models\KategoriPerangkat;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriSaranaController extends Controller
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

        return view('sarana-umum.kategori-sarana.index', compact('kategoriPerangkats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('sarana-umum.kategori-sarana.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriPerangkatRequest $request): RedirectResponse
    {
        $kategoriPerangkat = KategoriPerangkat::query()->create($request->validated());
        AuditLog::record('kategori-sarana', 'create', $kategoriPerangkat, null, $kategoriPerangkat->toArray());

        return redirect()
            ->route('sarana-umum.kategori-sarana.index')
            ->with('success', 'Kategori sarana berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriPerangkat $kategoriPerangkat): View
    {
        $kategoriPerangkat->load(['komponenPerangkats.unitKomputer.laboratorium']);

        return view('sarana-umum.kategori-sarana.show', compact('kategoriPerangkat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriPerangkat $kategoriPerangkat): View
    {
        return view('sarana-umum.kategori-sarana.edit', compact('kategoriPerangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriPerangkatRequest $request, KategoriPerangkat $kategoriPerangkat): RedirectResponse
    {
        $before = $kategoriPerangkat->toArray();
        $kategoriPerangkat->update($request->validated());
        AuditLog::record('kategori-sarana', 'update', $kategoriPerangkat, $before, $kategoriPerangkat->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.kategori-sarana.index')
            ->with('success', 'Kategori sarana berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPerangkat $kategoriPerangkat): RedirectResponse
    {
        $before = $kategoriPerangkat->toArray();
        $kategoriPerangkat->delete();
        AuditLog::record('kategori-sarana', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.kategori-sarana.index')
            ->with('success', 'Kategori sarana berhasil dihapus.');
    }
}
