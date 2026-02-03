@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Komponen Perangkat</h1>
            <p class="text-gray-600 mt-1">Kelola semua komponen perangkat di laboratorium</p>
        </div>
        <a href="{{ route('laboran.komponen-perangkat.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Komponen
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('laboran.komponen-perangkat.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <select name="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama }} ({{ $unit->laboratorium->nama }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <select name="kondisi" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="mati_total" {{ request('kondisi') === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                Filter
            </button>
            @if(request()->hasAny(['unit', 'kategori', 'kondisi']))
                <a href="{{ route('laboran.komponen-perangkat.index') }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Kode Inventaris</th>
                        <th class="px-6 py-4 text-left">Kategori</th>
                        <th class="px-6 py-4 text-left">Merk / Model</th>
                        <th class="px-6 py-4 text-left">Unit Komputer</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($komponenPerangkats as $komponen)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $komponen->kode_inventaris }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2">
                                    <x-heroicon-o-cube class="w-4 h-4 text-gray-400"/>
                                    {{ $komponen->kategori->nama }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $komponen->merk ?? '-' }}
                                @if($komponen->model)
                                    <span class="text-gray-400">/ {{ $komponen->model }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('laboran.unit-komputer.show', $komponen->unitKomputer) }}" class="text-blue-600 hover:underline">
                                    {{ $komponen->unitKomputer->nama }}
                                </a>
                                <span class="text-xs text-gray-400 block">{{ $komponen->unitKomputer->laboratorium->nama }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $komponen->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $komponen->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $komponen->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $komponen->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($komponen->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $komponen->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $komponen->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $komponen->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($komponen->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.komponen-perangkat.show', $komponen) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.komponen-perangkat.edit', $komponen) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.maintenance-log.create', ['komponen_id' => $komponen->id]) }}" class="text-purple-600 hover:text-purple-800" title="Lapor Masalah">
                                        <x-heroicon-o-wrench-screwdriver class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-komponen-{{ $komponen->id }}" action="{{ route('laboran.komponen-perangkat.destroy', $komponen) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-komponen-{{ $komponen->id }}', 'Hapus Komponen?', 'Data {{ $komponen->kategori->nama }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-cube class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data komponen perangkat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($komponenPerangkats->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $komponenPerangkats->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
