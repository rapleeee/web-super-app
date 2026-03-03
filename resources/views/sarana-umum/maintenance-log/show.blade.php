@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="text-gray-500 hover:text-gray-700">
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

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sarana</h3>
        <p class="font-medium text-gray-900">{{ $maintenanceLog->saranaUmum->nama }}</p>
        <p class="text-sm text-gray-600">{{ $maintenanceLog->saranaUmum->kode_inventaris }} • {{ $maintenanceLog->saranaUmum->lokasi }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Keluhan & Perbaikan</h3>
            <div>
                <p class="text-sm text-gray-500">Keluhan</p>
                <p class="text-gray-900">{{ $maintenanceLog->keluhan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Diagnosa</p>
                <p class="text-gray-900">{{ $maintenanceLog->diagnosa ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tindakan</p>
                <p class="text-gray-900">{{ $maintenanceLog->tindakan ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Catatan</p>
                <p class="text-gray-900">{{ $maintenanceLog->catatan ?: '-' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Detail Proses</h3>
            <p><span class="text-sm text-gray-500">Pelapor:</span> <span class="text-gray-900">{{ $maintenanceLog->pelapor?->name ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Teknisi:</span> <span class="text-gray-900">{{ $maintenanceLog->teknisi ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Biaya:</span> <span class="text-gray-900">{{ $maintenanceLog->biaya ? 'Rp '.number_format((float) $maintenanceLog->biaya, 0, ',', '.') : '-' }}</span></p>
            <p><span class="text-sm text-gray-500">SLA Deadline:</span> <span class="text-gray-900">{{ $maintenanceLog->sla_deadline?->format('d M Y') ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Status SLA:</span>
                @if($maintenanceLog->is_sla_breached)
                    <span class="text-red-600 font-medium">Melewati Deadline</span>
                @else
                    <span class="text-green-600 font-medium">Dalam Batas Waktu</span>
                @endif
            </p>
            <p><span class="text-sm text-gray-500">Tanggal Mulai:</span> <span class="text-gray-900">{{ $maintenanceLog->tanggal_mulai?->format('d M Y') ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Tanggal Selesai:</span> <span class="text-gray-900">{{ $maintenanceLog->tanggal_selesai?->format('d M Y') ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Durasi:</span> <span class="text-gray-900">{{ $maintenanceLog->durasi ? $maintenanceLog->durasi.' hari' : '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Kondisi Sebelum:</span> <span class="text-gray-900">{{ str_replace('_', ' ', ucfirst($maintenanceLog->kondisi_sebelum)) }}</span></p>
            <p><span class="text-sm text-gray-500">Kondisi Sesudah:</span> <span class="text-gray-900">{{ $maintenanceLog->kondisi_sesudah ? str_replace('_', ' ', ucfirst($maintenanceLog->kondisi_sesudah)) : '-' }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lampiran Bukti</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500 mb-2">Bukti Sebelum</p>
                @if($maintenanceLog->bukti_sebelum)
                    <a href="{{ Storage::url($maintenanceLog->bukti_sebelum) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>
                @else
                    <p class="text-gray-400">-</p>
                @endif
            </div>
            <div>
                <p class="text-gray-500 mb-2">Bukti Sesudah</p>
                @if($maintenanceLog->bukti_sesudah)
                    <a href="{{ Storage::url($maintenanceLog->bukti_sesudah) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>
                @else
                    <p class="text-gray-400">-</p>
                @endif
            </div>
            <div>
                <p class="text-gray-500 mb-2">Bukti Invoice</p>
                @if($maintenanceLog->bukti_invoice)
                    <a href="{{ Storage::url($maintenanceLog->bukti_invoice) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>
                @else
                    <p class="text-gray-400">-</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-end gap-3">
        <a href="{{ route('sarana-umum.maintenance-log.edit', $maintenanceLog) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-pencil-square class="w-4 h-4"/>
            Edit
        </a>
        <form id="delete-log-show" action="{{ route('sarana-umum.maintenance-log.destroy', $maintenanceLog) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete('delete-log-show', 'Hapus Log?', 'Data maintenance akan dihapus permanen!')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <x-heroicon-o-trash class="w-4 h-4"/>
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection
