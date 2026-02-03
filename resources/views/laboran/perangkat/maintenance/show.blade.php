@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.maintenance-log.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Detail Maintenance</h1>
            <p class="text-gray-600 mt-1">{{ $maintenanceLog->tanggal_lapor->format('d M Y') }}</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            {{ $maintenanceLog->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
            {{ $maintenanceLog->status === 'proses' ? 'bg-blue-100 text-blue-600' : '' }}
            {{ $maintenanceLog->status === 'selesai' ? 'bg-green-100 text-green-600' : '' }}
            {{ $maintenanceLog->status === 'tidak_bisa_diperbaiki' ? 'bg-red-100 text-red-600' : '' }}">
            {{ ucfirst(str_replace('_', ' ', $maintenanceLog->status)) }}
        </span>
    </div>

    {{-- Komponen Info --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-[#272125] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Informasi Komponen</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center">
                    <x-heroicon-o-cube class="w-7 h-7 text-gray-400"/>
                </div>
                <div class="flex-1">
                    <a href="{{ route('laboran.komponen-perangkat.show', $maintenanceLog->komponenPerangkat) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                        {{ $maintenanceLog->komponenPerangkat->kategori->nama }}
                    </a>
                    <p class="text-gray-600">{{ $maintenanceLog->komponenPerangkat->kode_inventaris }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $maintenanceLog->komponenPerangkat->merk ?? '' }} {{ $maintenanceLog->komponenPerangkat->model ?? '' }}
                        • {{ $maintenanceLog->komponenPerangkat->unitKomputer->nama }}
                        • {{ $maintenanceLog->komponenPerangkat->unitKomputer->laboratorium->nama }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
        <div class="flex items-center gap-2">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-3 h-3 rounded-full {{ $maintenanceLog->tanggal_lapor ? 'bg-[#272125]' : 'bg-gray-300' }}"></div>
                    <span class="text-sm font-medium text-gray-900">Dilaporkan</span>
                </div>
                <p class="text-sm text-gray-500 ml-5">{{ $maintenanceLog->tanggal_lapor->format('d M Y') }}</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-200"></div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-3 h-3 rounded-full {{ $maintenanceLog->tanggal_mulai ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                    <span class="text-sm font-medium text-gray-900">Mulai Perbaikan</span>
                </div>
                <p class="text-sm text-gray-500 ml-5">{{ $maintenanceLog->tanggal_mulai?->format('d M Y') ?? '-' }}</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-200"></div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-3 h-3 rounded-full {{ $maintenanceLog->tanggal_selesai ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                    <span class="text-sm font-medium text-gray-900">Selesai</span>
                </div>
                <p class="text-sm text-gray-500 ml-5">{{ $maintenanceLog->tanggal_selesai?->format('d M Y') ?? '-' }}</p>
            </div>
        </div>
        @if($maintenanceLog->durasi)
            <div class="mt-4 text-center">
                <span class="inline-flex items-center gap-1 text-sm text-gray-500">
                    <x-heroicon-o-clock class="w-4 h-4"/>
                    Durasi perbaikan: <span class="font-medium text-gray-900">{{ $maintenanceLog->durasi }} hari</span>
                </span>
            </div>
        @endif
    </div>

    {{-- Detail --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Keluhan & Diagnosa --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Keluhan & Diagnosa</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Keluhan / Masalah</p>
                    <p class="text-gray-900">{{ $maintenanceLog->keluhan }}</p>
                </div>
                @if($maintenanceLog->diagnosa)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Diagnosa</p>
                        <p class="text-gray-900">{{ $maintenanceLog->diagnosa }}</p>
                    </div>
                @endif
                @if($maintenanceLog->tindakan)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tindakan</p>
                        <p class="text-gray-900">{{ $maintenanceLog->tindakan }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info Perbaikan --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Perbaikan</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Pelapor</p>
                        <p class="text-gray-900">{{ $maintenanceLog->pelapor->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Teknisi</p>
                        <p class="text-gray-900">{{ $maintenanceLog->teknisi ?? '-' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Biaya</p>
                    <p class="text-gray-900 font-medium">
                        {{ $maintenanceLog->biaya ? 'Rp ' . number_format($maintenanceLog->biaya, 0, ',', '.') : '-' }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kondisi Sebelum</p>
                        @if($maintenanceLog->kondisi_sebelum)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $maintenanceLog->kondisi_sebelum === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $maintenanceLog->kondisi_sebelum === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $maintenanceLog->kondisi_sebelum === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $maintenanceLog->kondisi_sebelum === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ str_replace('_', ' ', ucfirst($maintenanceLog->kondisi_sebelum)) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kondisi Sesudah</p>
                        @if($maintenanceLog->kondisi_sesudah)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $maintenanceLog->kondisi_sesudah === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $maintenanceLog->kondisi_sesudah === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $maintenanceLog->kondisi_sesudah === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $maintenanceLog->kondisi_sesudah === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ str_replace('_', ' ', ucfirst($maintenanceLog->kondisi_sesudah)) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>
                @if($maintenanceLog->catatan)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Catatan</p>
                        <p class="text-gray-900">{{ $maintenanceLog->catatan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('laboran.maintenance-log.edit', $maintenanceLog) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                <x-heroicon-o-pencil-square class="w-4 h-4"/>
                Edit
            </a>
            <form id="delete-log-show" action="{{ route('laboran.maintenance-log.destroy', $maintenanceLog) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-log-show', 'Hapus Log?', 'Data maintenance akan dihapus permanen!')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-4 h-4"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
