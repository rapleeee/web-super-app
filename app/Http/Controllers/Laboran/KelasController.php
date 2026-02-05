<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $kelass = Kelas::query()
            ->when($request->tingkat, fn ($q) => $q->where('tingkat', $request->tingkat))
            ->when($request->jurusan, fn ($q) => $q->where('jurusan', $request->jurusan))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('tingkat')
            ->orderBy('jurusan')
            ->orderBy('rombel')
            ->paginate(15)
            ->withQueryString();

        return view('laboran.data-master.kelas.index', compact('kelass'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('laboran.data-master.kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tingkat' => ['required', 'in:10,11,12'],
            'jurusan' => ['required', 'in:RPL,DKV,TKJ'],
            'rombel' => [
                'required',
                'string',
                'max:5',
                Rule::unique('kelas')->where(function ($query) use ($request) {
                    return $query->where('tingkat', $request->tingkat)
                        ->where('jurusan', $request->jurusan);
                }),
            ],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'rombel.unique' => 'Kelas dengan tingkat, jurusan, dan rombel yang sama sudah ada.',
        ]);

        Kelas::create($validated);

        return redirect()
            ->route('laboran.data-master.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela): View
    {
        return view('laboran.data-master.kelas.show', ['kelas' => $kela]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kela): View
    {
        return view('laboran.data-master.kelas.edit', ['kelas' => $kela]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $validated = $request->validate([
            'tingkat' => ['required', 'in:10,11,12'],
            'jurusan' => ['required', 'in:RPL,DKV,TKJ'],
            'rombel' => [
                'required',
                'string',
                'max:5',
                Rule::unique('kelas')->where(function ($query) use ($request) {
                    return $query->where('tingkat', $request->tingkat)
                        ->where('jurusan', $request->jurusan);
                })->ignore($kela->id),
            ],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'rombel.unique' => 'Kelas dengan tingkat, jurusan, dan rombel yang sama sudah ada.',
        ]);

        $kela->update($validated);

        return redirect()
            ->route('laboran.data-master.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kela): RedirectResponse
    {
        $kela->delete();

        return redirect()
            ->route('laboran.data-master.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
