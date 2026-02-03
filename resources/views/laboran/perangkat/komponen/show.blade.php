@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('laboran.komponen-perangkat.index') }}" class="text-gray-500 hover:text-gray-700">
                <x-heroicon-o-arrow-left class="w-6 h-6"/>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $komponenPerangkat->kategori->nama }}</h1>
                <p class="text-gray-600 mt-1">{{ $komponenPerangkat->kode_inventaris }}</p>
            </div>
        </div>
        <a href="{{ route('laboran.maintenance-log.create', ['komponen_id' => $komponenPerangkat->id]) }}"
           class="inline-flex items-center gap-2 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
            <x-heroicon-o-wrench-screwdriver class="w-5 h-5"/>
            Lapor Masalah
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-[#272125] px-6 py-6 text-center">
                @if($komponenPerangkat->foto)
                    <img src="{{ Storage::url($komponenPerangkat->foto) }}" alt="Foto komponen" class="w-24 h-24 mx-auto rounded-lg object-cover mb-3">
                @else
                    <div class="w-16 h-16 mx-auto rounded-full bg-white/10 flex items-center justify-center mb-3">
                        <x-heroicon-o-cube class="w-8 h-8 text-white"/>
                    </div>
                @endif
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $komponenPerangkat->kondisi === 'baik' ? 'bg-green-500 text-white' : '' }}
                    {{ $komponenPerangkat->kondisi === 'rusak_ringan' ? 'bg-yellow-500 text-white' : '' }}
                    {{ $komponenPerangkat->kondisi === 'rusak_berat' ? 'bg-orange-500 text-white' : '' }}
                    {{ $komponenPerangkat->kondisi === 'mati_total' ? 'bg-red-500 text-white' : '' }}">
                    {{ str_replace('_', ' ', ucfirst($komponenPerangkat->kondisi)) }}
                </span>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Kategori</p>
                    <p class="font-medium text-gray-900">{{ $komponenPerangkat->kategori->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Unit Komputer</p>
                    <a href="{{ route('laboran.unit-komputer.show', $komponenPerangkat->unitKomputer) }}" class="font-medium text-blue-600 hover:underline">
                        {{ $komponenPerangkat->unitKomputer->nama }}
                    </a>
                    <p class="text-xs text-gray-400">{{ $komponenPerangkat->unitKomputer->laboratorium->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Merk / Model</p>
                    <p class="font-medium text-gray-900">{{ $komponenPerangkat->merk ?? '-' }} {{ $komponenPerangkat->model ? '/ ' . $komponenPerangkat->model : '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Seri</p>
                    <p class="font-medium text-gray-900">{{ $komponenPerangkat->nomor_seri ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tahun Pengadaan</p>
                    <p class="font-medium text-gray-900">{{ $komponenPerangkat->tahun_pengadaan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $komponenPerangkat->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $komponenPerangkat->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $komponenPerangkat->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ str_replace('_', ' ', ucfirst($komponenPerangkat->status)) }}
                    </span>
                </div>
                @if($komponenPerangkat->spesifikasi)
                    <div>
                        <p class="text-sm text-gray-500">Spesifikasi</p>
                        <p class="text-gray-900 text-sm whitespace-pre-wrap">{{ $komponenPerangkat->spesifikasi }}</p>
                    </div>
                @endif
                @if($komponenPerangkat->keterangan)
                    <div>
                        <p class="text-sm text-gray-500">Keterangan</p>
                        <p class="text-gray-900">{{ $komponenPerangkat->keterangan }}</p>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t flex flex-col gap-2">
                <a href="{{ route('laboran.komponen-perangkat.edit', $komponenPerangkat) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    <x-heroicon-o-pencil-square class="w-4 h-4"/>
                    Edit Komponen
                </a>
                <form id="delete-komponen-show" action="{{ route('laboran.komponen-perangkat.destroy', $komponenPerangkat) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('delete-komponen-show', 'Hapus Komponen?', 'Data {{ $komponenPerangkat->kategori->nama }} akan dihapus permanen!')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <x-heroicon-o-trash class="w-4 h-4"/>
                        Hapus Komponen
                    </button>
                </form>
            </div>
        </div>

        {{-- Maintenance History --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Maintenance</h3>
                <span class="text-sm text-gray-500">{{ $komponenPerangkat->maintenanceLogs->count() }} log</span>
            </div>

            <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                @forelse ($komponenPerangkat->maintenanceLogs as $log)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium
                                        {{ $log->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $log->status === 'proses' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $log->status === 'selesai' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $log->status === 'tidak_bisa_diperbaiki' ? 'bg-red-100 text-red-600' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $log->tanggal_lapor->format('d M Y') }}</span>
                                </div>
                                <p class="text-gray-900 font-medium">{{ $log->keluhan }}</p>
                                @if($log->diagnosa)
                                    <p class="text-sm text-gray-600 mt-1">Diagnosa: {{ $log->diagnosa }}</p>
                                @endif
                                @if($log->tindakan)
                                    <p class="text-sm text-gray-600">Tindakan: {{ $log->tindakan }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span>Pelapor: {{ $log->pelapor->name }}</span>
                                    @if($log->teknisi)
                                        <span>Teknisi: {{ $log->teknisi }}</span>
                                    @endif
                                    @if($log->biaya)
                                        <span>Biaya: Rp {{ number_format($log->biaya, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('laboran.maintenance-log.show', $log) }}" class="text-blue-600 hover:text-blue-800">
                                    <x-heroicon-o-eye class="w-5 h-5"/>
                                </a>
                                <a href="{{ route('laboran.maintenance-log.edit', $log) }}" class="text-yellow-600 hover:text-yellow-800">
                                    <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <x-heroicon-o-wrench-screwdriver class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                        <p>Belum ada riwayat maintenance.</p>
                        <a href="{{ route('laboran.maintenance-log.create', ['komponen_id' => $komponenPerangkat->id]) }}" class="mt-4 inline-flex items-center gap-2 text-[#272125] hover:underline">
                            <x-heroicon-o-plus class="w-4 h-4"/>
                            Lapor masalah pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
