<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $mataPelajarans = MataPelajaran::query()
            ->when($request->search, fn ($q) => $q->where('nama', 'like', "%{$request->search}%")
                ->orWhere('kode', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('laboran.data-master.mata-pelajaran.index', compact('mataPelajarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('laboran.data-master.mata-pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:mata_pelajarans,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        MataPelajaran::create($validated);

        return redirect()
            ->route('laboran.data-master.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran): View
    {
        return view('laboran.data-master.mata-pelajaran.show', compact('mataPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran): View
    {
        return view('laboran.data-master.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:mata_pelajarans,kode,'.$mataPelajaran->id],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $mataPelajaran->update($validated);

        return redirect()
            ->route('laboran.data-master.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        $mataPelajaran->delete();

        return redirect()
            ->route('laboran.data-master.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
