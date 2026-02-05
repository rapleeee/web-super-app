<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $gurus = Guru::query()
            ->when($request->search, fn ($q) => $q->where('nama', 'like', "%{$request->search}%")
                ->orWhere('nip', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('laboran.data-master.guru.index', compact('gurus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('laboran.data-master.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:30', 'unique:gurus,nip'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Guru::create($validated);

        return redirect()
            ->route('laboran.data-master.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru): View
    {
        return view('laboran.data-master.guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru): View
    {
        return view('laboran.data-master.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:30', 'unique:gurus,nip,'.$guru->id],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $guru->update($validated);

        return redirect()
            ->route('laboran.data-master.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        $guru->delete();

        return redirect()
            ->route('laboran.data-master.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
