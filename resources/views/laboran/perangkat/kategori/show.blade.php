@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.kategori-perangkat.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kategori Perangkat</h1>
            <p class="text-gray-600 mt-1">{{ $kategoriPerangkat->nama }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Detail Card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-[#272125] px-6 py-8 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-white/10 flex items-center justify-center mb-4">
                    @if($kategoriPerangkat->icon)
                        <x-dynamic-component :component="'heroicon-o-' . $kategoriPerangkat->icon" class="w-10 h-10 text-white"/>
                    @else
                        <x-heroicon-o-cube class="w-10 h-10 text-white"/>
                    @endif
                </div>
                <h2 class="text-xl font-semibold text-white">{{ $kategoriPerangkat->nama }}</h2>
                <p class="text-white/70">{{ $kategoriPerangkat->kode }}</p>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kategoriPerangkat->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($kategoriPerangkat->status) }}
                    </span>
                </div>
                @if($kategoriPerangkat->deskripsi)
                    <div>
                        <p class="text-sm text-gray-500">Deskripsi</p>
                        <p class="text-gray-900">{{ $kategoriPerangkat->deskripsi }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Total Komponen</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $kategoriPerangkat->komponenPerangkats->count() }}</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-end gap-3">
                <a href="{{ route('laboran.kategori-perangkat.edit', $kategoriPerangkat) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    <x-heroicon-o-pencil-square class="w-4 h-4"/>
                    Edit
                </a>
                <form id="delete-kategori-show" action="{{ route('laboran.kategori-perangkat.destroy', $kategoriPerangkat) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('delete-kategori-show', 'Hapus Kategori?', 'Data {{ $kategoriPerangkat->nama }} akan dihapus permanen!')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <x-heroicon-o-trash class="w-4 h-4"/>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- Komponen List --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Komponen</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Kode Inventaris</th>
                            <th class="px-6 py-3 text-left">Unit</th>
                            <th class="px-6 py-3 text-left">Lab</th>
                            <th class="px-6 py-3 text-center">Kondisi</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kategoriPerangkat->komponenPerangkats as $komponen)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium">{{ $komponen->kode_inventaris }}</td>
                                <td class="px-6 py-3">{{ $komponen->unitKomputer->nama }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $komponen->unitKomputer->laboratorium->nama }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $komponen->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $komponen->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $komponen->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $komponen->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ str_replace('_', ' ', ucfirst($komponen->kondisi)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('laboran.komponen-perangkat.show', $komponen) }}" class="text-blue-600 hover:text-blue-800">
                                        <x-heroicon-o-eye class="w-5 h-5 inline"/>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada komponen di kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
