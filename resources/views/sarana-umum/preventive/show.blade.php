@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.preventive-maintenance.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Detail Preventive Maintenance</h1>
            <p class="mt-1 text-gray-600">{{ $preventiveMaintenance->nama_tugas }}</p>
        </div>
        @if(!$preventiveMaintenance->is_active)
            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">Non Aktif</span>
        @elseif($preventiveMaintenance->is_overdue)
            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Overdue</span>
        @else
            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Sarana</h3>
            <p><span class="text-sm text-gray-500">Nama:</span> <span class="text-gray-900">{{ $preventiveMaintenance->saranaUmum?->nama ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Kode:</span> <span class="text-gray-900">{{ $preventiveMaintenance->saranaUmum?->kode_inventaris ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Lokasi:</span> <span class="text-gray-900">{{ $preventiveMaintenance->saranaUmum?->lokasi ?? '-' }}</span></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Jadwal</h3>
            <p><span class="text-sm text-gray-500">Interval:</span> <span class="text-gray-900">{{ $preventiveMaintenance->interval_hari }} hari</span></p>
            <p><span class="text-sm text-gray-500">Toleransi:</span> <span class="text-gray-900">{{ $preventiveMaintenance->toleransi_hari }} hari</span></p>
            <p><span class="text-sm text-gray-500">Mulai:</span> <span class="text-gray-900">{{ $preventiveMaintenance->tanggal_mulai->format('d M Y') }}</span></p>
            <p><span class="text-sm text-gray-500">Terakhir:</span> <span class="text-gray-900">{{ $preventiveMaintenance->tanggal_maintenance_terakhir?->format('d M Y') ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Berikutnya:</span> <span class="text-gray-900">{{ $preventiveMaintenance->tanggal_maintenance_berikutnya->format('d M Y') }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
        <h3 class="text-lg font-semibold text-gray-900">Deskripsi</h3>
        <p class="text-gray-700">{{ $preventiveMaintenance->deskripsi ?: '-' }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-end gap-3">
        <form action="{{ route('sarana-umum.preventive-maintenance.complete', $preventiveMaintenance) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-green-700 hover:bg-green-100 transition">
                <x-heroicon-o-check class="w-4 h-4"/>
                Tandai Selesai Hari Ini
            </button>
        </form>
        <a href="{{ route('sarana-umum.preventive-maintenance.edit', $preventiveMaintenance) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">
            <x-heroicon-o-pencil-square class="w-4 h-4"/>
            Edit
        </a>
    </div>
</div>
@endsection
