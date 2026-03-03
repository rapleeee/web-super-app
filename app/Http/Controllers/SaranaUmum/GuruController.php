<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaranaUmum\StoreGuruRequest;
use App\Http\Requests\SaranaUmum\UpdateGuruRequest;
use App\Models\AuditLog;
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
            ->when($request->search, fn ($query) => $query->where('nama', 'like', "%{$request->search}%")
                ->orWhere('nip', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($query) => $query->where('status', $request->status))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('sarana-umum.data-guru.index', compact('gurus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('sarana-umum.data-guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuruRequest $request): RedirectResponse
    {
        $guru = Guru::query()->create($request->validated());
        AuditLog::record('data-guru', 'create', $guru, null, $guru->toArray());

        return redirect()
            ->route('sarana-umum.data-guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru): View
    {
        return view('sarana-umum.data-guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru): View
    {
        return view('sarana-umum.data-guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuruRequest $request, Guru $guru): RedirectResponse
    {
        $before = $guru->toArray();
        $guru->update($request->validated());
        AuditLog::record('data-guru', 'update', $guru, $before, $guru->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.data-guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        $before = $guru->toArray();
        $guru->delete();
        AuditLog::record('data-guru', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.data-guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
