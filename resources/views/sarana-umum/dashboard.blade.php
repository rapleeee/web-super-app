@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Sarana Umum</h1>
            <p class="text-gray-600 mt-1">Monitoring kondisi sarana umum sekolah secara terpusat.</p>
        </div>
        <a href="{{ route('sarana-umum.data-sarana.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition text-sm">
            <x-heroicon-o-plus class="w-4 h-4"/>
            Tambah Sarana
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <x-heroicon-o-building-office class="w-6 h-6 text-blue-600"/>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalSarana }}</p>
                <p class="text-sm text-gray-500">Total Sarana</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600"/>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $saranaBermasalah }}</p>
                <p class="text-sm text-gray-500">Sarana Bermasalah</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <x-heroicon-o-wrench-screwdriver class="w-6 h-6 text-yellow-600"/>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $dalamPerbaikan }}</p>
                <p class="text-sm text-gray-500">Dalam Perbaikan</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('sarana-umum.preventive-maintenance.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 hover:shadow-sm transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-600"/>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Preventive</p>
                    <p class="text-xs text-gray-500">Jadwal maintenance berkala</p>
                </div>
            </div>
        </a>
        <a href="{{ route('sarana-umum.data-sarana.import') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-amber-300 hover:shadow-sm transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 text-amber-600"/>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Import Massal</p>
                    <p class="text-xs text-gray-500">Upload CSV data sarana</p>
                </div>
            </div>
        </a>
        <a href="{{ route('sarana-umum.audit-log.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-emerald-300 hover:shadow-sm transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <x-heroicon-o-shield-check class="w-5 h-5 text-emerald-600"/>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Audit Log</p>
                    <p class="text-xs text-gray-500">Jejak aktivitas pengguna</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Kondisi Sarana</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Baik</span>
                    <span class="text-sm font-semibold text-green-600">{{ $kondisiStats['baik'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Rusak Ringan</span>
                    <span class="text-sm font-semibold text-yellow-600">{{ $kondisiStats['rusak_ringan'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Rusak Berat</span>
                    <span class="text-sm font-semibold text-orange-600">{{ $kondisiStats['rusak_berat'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-600">Mati Total</span>
                    <span class="text-sm font-semibold text-red-600">{{ $kondisiStats['mati_total'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Status Sarana</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Aktif</span>
                    <span class="text-sm font-semibold text-green-600">{{ $statusStats['aktif'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Dalam Perbaikan</span>
                    <span class="text-sm font-semibold text-yellow-600">{{ $statusStats['dalam_perbaikan'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-600">Tidak Aktif</span>
                    <span class="text-sm font-semibold text-red-600">{{ $statusStats['tidak_aktif'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Data Sarana Terbaru</h2>
            <a href="{{ route('sarana-umum.data-sarana.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Lokasi</th>
                        <th class="px-6 py-3 text-center">Kondisi</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($saranaTerbaru as $sarana)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $sarana->kode_inventaris }}</td>
                            <td class="px-6 py-3">{{ $sarana->nama }}</td>
                            <td class="px-6 py-3">{{ $sarana->lokasi }}</td>
                            <td class="px-6 py-3 text-center">{{ str_replace('_', ' ', ucfirst($sarana->kondisi)) }}</td>
                            <td class="px-6 py-3 text-center">{{ str_replace('_', ' ', ucfirst($sarana->status)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada data sarana umum.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
