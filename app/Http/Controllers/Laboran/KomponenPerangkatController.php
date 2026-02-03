<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreKomponenPerangkatRequest;
use App\Http\Requests\Laboran\UpdateKomponenPerangkatRequest;
use App\Models\KategoriPerangkat;
use App\Models\KomponenPerangkat;
use App\Models\UnitKomputer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KomponenPerangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $komponenPerangkats = KomponenPerangkat::query()
            ->with(['unitKomputer.laboratorium', 'kategori'])
            ->when($request->unit, fn ($q, $id) => $q->where('unit_komputer_id', $id))
            ->when($request->kategori, fn ($q, $id) => $q->where('kategori_id', $id))
            ->when($request->kondisi, fn ($q, $kondisi) => $q->where('kondisi', $kondisi))
            ->latest()
            ->paginate(10);

        $units = UnitKomputer::with('laboratorium')->get();
        $kategoris = KategoriPerangkat::where('status', 'aktif')->get();

        return view('laboran.perangkat.komponen.index', compact('komponenPerangkats', 'units', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $units = UnitKomputer::with('laboratorium')->get();
        $kategoris = KategoriPerangkat::where('status', 'aktif')->get();

        return view('laboran.perangkat.komponen.create', compact('units', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKomponenPerangkatRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('komponen-perangkat', 'public');
        }

        $komponen = KomponenPerangkat::create($data);

        // Jika dari halaman unit komputer, redirect kembali ke sana
        if ($request->has('from_unit')) {
            return redirect()
                ->route('laboran.unit-komputer.show', $komponen->unit_komputer_id)
                ->with('success', 'Komponen perangkat berhasil ditambahkan.');
        }

        return redirect()
            ->route('laboran.komponen-perangkat.index')
            ->with('success', 'Komponen perangkat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KomponenPerangkat $komponenPerangkat): View
    {
        $komponenPerangkat->load([
            'unitKomputer.laboratorium',
            'kategori',
            'maintenanceLogs' => fn ($q) => $q->latest('tanggal_lapor'),
            'maintenanceLogs.pelapor',
        ]);

        return view('laboran.perangkat.komponen.show', compact('komponenPerangkat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KomponenPerangkat $komponenPerangkat): View
    {
        $units = UnitKomputer::with('laboratorium')->get();
        $kategoris = KategoriPerangkat::where('status', 'aktif')->get();

        return view('laboran.perangkat.komponen.edit', compact('komponenPerangkat', 'units', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKomponenPerangkatRequest $request, KomponenPerangkat $komponenPerangkat): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($komponenPerangkat->foto) {
                Storage::disk('public')->delete($komponenPerangkat->foto);
            }
            $data['foto'] = $request->file('foto')->store('komponen-perangkat', 'public');
        }

        $komponenPerangkat->update($data);

        return redirect()
            ->route('laboran.komponen-perangkat.index')
            ->with('success', 'Komponen perangkat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KomponenPerangkat $komponenPerangkat): RedirectResponse
    {
        if ($komponenPerangkat->foto) {
            Storage::disk('public')->delete($komponenPerangkat->foto);
        }

        $komponenPerangkat->delete();

        return redirect()
            ->route('laboran.komponen-perangkat.index')
            ->with('success', 'Komponen perangkat berhasil dihapus.');
    }
}
