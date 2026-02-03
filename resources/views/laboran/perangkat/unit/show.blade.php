@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('laboran.unit-komputer.index') }}" class="text-gray-500 hover:text-gray-700">
                <x-heroicon-o-arrow-left class="w-6 h-6"/>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $unitKomputer->nama }}</h1>
                <p class="text-gray-600 mt-1">{{ $unitKomputer->kode_unit }} • {{ $unitKomputer->laboratorium->nama }}</p>
            </div>
        </div>
        <a href="{{ route('laboran.komponen-perangkat.create', ['unit_komputer_id' => $unitKomputer->id]) }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Komponen
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Info Card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-[#272125] px-6 py-6 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-white/10 flex items-center justify-center mb-3">
                    <x-heroicon-o-computer-desktop class="w-8 h-8 text-white"/>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $unitKomputer->kondisi === 'baik' ? 'bg-green-500 text-white' : '' }}
                    {{ $unitKomputer->kondisi === 'rusak_ringan' ? 'bg-yellow-500 text-white' : '' }}
                    {{ $unitKomputer->kondisi === 'rusak_berat' ? 'bg-orange-500 text-white' : '' }}
                    {{ $unitKomputer->kondisi === 'mati_total' ? 'bg-red-500 text-white' : '' }}">
                    {{ str_replace('_', ' ', ucfirst($unitKomputer->kondisi)) }}
                </span>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Laboratorium</p>
                    <p class="font-medium text-gray-900">{{ $unitKomputer->laboratorium->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Meja</p>
                    <p class="font-medium text-gray-900">{{ $unitKomputer->nomor_meja ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $unitKomputer->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $unitKomputer->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $unitKomputer->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ str_replace('_', ' ', ucfirst($unitKomputer->status)) }}
                    </span>
                </div>
                @if($unitKomputer->keterangan)
                    <div>
                        <p class="text-sm text-gray-500">Keterangan</p>
                        <p class="text-gray-900">{{ $unitKomputer->keterangan }}</p>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t flex flex-col gap-2">
                <a href="{{ route('laboran.unit-komputer.edit', $unitKomputer) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    <x-heroicon-o-pencil-square class="w-4 h-4"/>
                    Edit Unit
                </a>
                <form id="delete-unit-show" action="{{ route('laboran.unit-komputer.destroy', $unitKomputer) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('delete-unit-show', 'Hapus Unit?', 'Data {{ $unitKomputer->nama }} dan semua komponennya akan dihapus permanen!')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <x-heroicon-o-trash class="w-4 h-4"/>
                        Hapus Unit
                    </button>
                </form>
            </div>
        </div>

        {{-- Komponen List --}}
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Komponen</h3>
                <span class="text-sm text-gray-500">{{ $unitKomputer->komponenPerangkats->count() }} item</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($unitKomputer->komponenPerangkats as $komponen)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <x-heroicon-o-cube class="w-6 h-6 text-gray-400"/>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ $komponen->kategori->nama }}</span>
                                        <span class="text-xs text-gray-400">{{ $komponen->kode_inventaris }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $komponen->merk ?? '-' }} {{ $komponen->model ?? '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $komponen->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $komponen->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $komponen->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $komponen->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($komponen->kondisi)) }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('laboran.komponen-perangkat.show', $komponen) }}" class="text-blue-600 hover:text-blue-800">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.maintenance-log.create', ['komponen_id' => $komponen->id]) }}" class="text-yellow-600 hover:text-yellow-800" title="Lapor Masalah">
                                        <x-heroicon-o-wrench-screwdriver class="w-5 h-5"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if($komponen->maintenanceLogs->isNotEmpty())
                            @php $lastLog = $komponen->maintenanceLogs->first(); @endphp
                            <div class="mt-2 ml-16 text-xs text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <x-heroicon-o-clock class="w-3 h-3"/>
                                    Terakhir maintenance: {{ $lastLog->tanggal_lapor->format('d M Y') }}
                                    <span class="px-1.5 py-0.5 rounded text-xs
                                        {{ $lastLog->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $lastLog->status === 'proses' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $lastLog->status === 'selesai' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $lastLog->status === 'tidak_bisa_diperbaiki' ? 'bg-red-100 text-red-600' : '' }}">
                                        {{ ucfirst($lastLog->status) }}
                                    </span>
                                </span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <x-heroicon-o-cube class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                        <p>Belum ada komponen di unit ini.</p>
                        <a href="{{ route('laboran.komponen-perangkat.create', ['unit_komputer_id' => $unitKomputer->id]) }}" class="mt-4 inline-flex items-center gap-2 text-[#272125] hover:underline">
                            <x-heroicon-o-plus class="w-4 h-4"/>
                            Tambah komponen pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
