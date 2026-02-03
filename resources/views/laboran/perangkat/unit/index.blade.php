@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Unit Komputer</h1>
            <p class="text-gray-600 mt-1">Kelola unit komputer di setiap laboratorium</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('laboran.unit-komputer.import') }}"
               class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <x-heroicon-o-arrow-up-tray class="w-5 h-5"/>
                Import
            </a>
            <a href="{{ route('laboran.unit-komputer.create') }}"
               class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
                <x-heroicon-o-plus class="w-5 h-5"/>
                Tambah Unit
            </a>
        </div>
    </div>

    {{-- Import Errors Alert --}}
    @if(session('import_errors'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"/>
                <div>
                    <h4 class="font-medium text-yellow-800">Beberapa baris gagal diimport:</h4>
                    <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('laboran.unit-komputer.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama unit..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
            </div>
            <div class="w-48">
                <select name="laboratorium" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Lab</option>
                    @foreach ($laboratoriums as $lab)
                        <option value="{{ $lab->id }}" {{ request('laboratorium') == $lab->id ? 'selected' : '' }}>{{ $lab->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <select name="kondisi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="mati_total" {{ request('kondisi') === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                </select>
            </div>
            <div class="w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="dalam_perbaikan" {{ request('status') === 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                    <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5"/>
                </button>
                @if(request()->hasAny(['search', 'laboratorium', 'kondisi', 'status']))
                    <a href="{{ route('laboran.unit-komputer.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5"/>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4 text-left">Kode Unit</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Laboratorium</th>
                        <th class="px-6 py-4 text-center">Meja</th>
                        <th class="px-6 py-4 text-center">Komponen</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($unitKomputers as $index => $unit)
                        <tr class="hover:bg-gray-50 cursor-pointer group" onclick="window.location='{{ route('laboran.unit-komputer.show', $unit) }}'">
                            <td class="px-6 py-4 text-center text-gray-500">{{ $unitKomputers->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $unit->kode_unit }}</td>
                            <td class="px-6 py-4 group-hover:text-blue-600 transition">{{ $unit->nama }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $unit->laboratorium->nama }}</td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $unit->nomor_meja ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $unit->komponen_perangkats_count }} item
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $unit->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $unit->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $unit->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $unit->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($unit->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $unit->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $unit->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $unit->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($unit->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.unit-komputer.edit', $unit) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-unit-{{ $unit->id }}" action="{{ route('laboran.unit-komputer.destroy', $unit) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-unit-{{ $unit->id }}', 'Hapus Unit?', 'Data {{ $unit->nama }} dan semua komponennya akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-computer-desktop class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data unit komputer.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($unitKomputers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $unitKomputers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
