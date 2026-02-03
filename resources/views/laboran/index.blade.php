@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Laboran</h1>
            <p class="text-gray-600 mt-1">Selamat datang, {{ auth()->user()->name }}.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('laboran.maintenance-log.create') }}"
               class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                <x-heroicon-o-exclamation-triangle class="w-4 h-4"/>
                Lapor Masalah
            </a>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition text-sm">
                    <x-heroicon-o-document-arrow-down class="w-4 h-4"/>
                    Export Laporan
                    <x-heroicon-o-chevron-down class="w-4 h-4"/>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Laporan Mingguan</p>
                    <a href="{{ route('laboran.export.weekly-report') }}"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Minggu Ini ({{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M') }})
                    </a>
                    <a href="{{ route('laboran.export.weekly-report', ['start_date' => now()->subWeek()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->subWeek()->endOfWeek()->format('Y-m-d')]) }}"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Minggu Lalu ({{ now()->subWeek()->startOfWeek()->format('d M') }} - {{ now()->subWeek()->endOfWeek()->format('d M') }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <x-heroicon-o-check-circle class="w-6 h-6 text-green-600"/>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $unitAktif }}</p>
                <p class="text-sm text-gray-500">Unit Aktif</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                <x-heroicon-o-exclamation-circle class="w-6 h-6 text-red-600"/>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $unitBermasalah }}</p>
                <p class="text-sm text-gray-500">Unit Bermasalah</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <x-heroicon-o-wrench-screwdriver class="w-6 h-6 text-blue-600"/>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $selesaiBulanIni }}</p>
                <p class="text-sm text-gray-500">Selesai Bulan Ini</p>
            </div>
        </div>
    </div>

    {{-- Work Area: Tabs for Pending & In Progress --}}
    <div class="bg-white rounded-xl border border-gray-200" x-data="{ tab: 'pending' }">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="tab = 'pending'"
                        :class="tab === 'pending' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition">
                    Laporan Masuk (Pending)
                    @if($pendingLogs->count() > 0)
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-600">{{ $pendingLogs->count() }}</span>
                    @endif
                </button>
                <button @click="tab = 'proses'"
                        :class="tab === 'proses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition">
                    Sedang Dikerjakan (Proses)
                    @if($prosesLogs->count() > 0)
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-600">{{ $prosesLogs->count() }}</span>
                    @endif
                </button>
            </nav>
        </div>

        {{-- Pending Tab --}}
        <div x-show="tab === 'pending'" class="p-5">
            @forelse ($pendingLogs as $log)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5"/>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $log->komponenPerangkat->kategori->nama ?? '-' }}
                                <span class="text-gray-500 font-normal">pada</span>
                                {{ $log->komponenPerangkat->unitKomputer->nama ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ Str::limit($log->keluhan, 50) }}
                                • {{ $log->tanggal_lapor->format('d M Y') }}
                                • <span class="text-xs">{{ $log->komponenPerangkat->unitKomputer->laboratorium->nama ?? '' }}</span>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('laboran.maintenance-log.edit', $log) }}"
                       class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition">
                        Proses
                    </a>
                </div>
            @empty
                <div class="text-center py-10">
                    <x-heroicon-o-inbox class="w-12 h-12 mx-auto text-gray-300 mb-2"/>
                    <p class="text-gray-500">Tidak ada laporan pending.</p>
                </div>
            @endforelse
            @if($pendingLogs->count() > 0)
                <div class="mt-4 text-center">
                    <a href="{{ route('laboran.maintenance-log.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:underline">
                        Lihat semua laporan pending →
                    </a>
                </div>
            @endif
        </div>

        {{-- In Progress Tab --}}
        <div x-show="tab === 'proses'" class="p-5" style="display: none;">
            @forelse ($prosesLogs as $log)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <x-heroicon-o-cog-6-tooth class="w-5 h-5"/>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $log->komponenPerangkat->kategori->nama ?? '-' }}
                                <span class="text-gray-500 font-normal">pada</span>
                                {{ $log->komponenPerangkat->unitKomputer->nama ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ Str::limit($log->keluhan, 50) }}
                                • {{ $log->tanggal_lapor->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('laboran.maintenance-log.edit', $log) }}"
                       class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                        Selesaikan
                    </a>
                </div>
            @empty
                <div class="text-center py-10">
                    <x-heroicon-o-check-circle class="w-12 h-12 mx-auto text-gray-300 mb-2"/>
                    <p class="text-gray-500">Tidak ada pekerjaan yang sedang berlangsung.</p>
                </div>
            @endforelse
            @if($prosesLogs->count() > 0)
                <div class="mt-4 text-center">
                    <a href="{{ route('laboran.maintenance-log.index', ['status' => 'proses']) }}" class="text-sm text-blue-600 hover:underline">
                        Lihat semua pekerjaan →
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Statistics Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Unit Condition Chart --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Kondisi Unit Komputer</h2>
            <div class="flex items-center gap-6">
                {{-- Donut Chart Placeholder --}}
                <div class="relative w-32 h-32 flex-shrink-0">
                    @php
                        $total = array_sum($unitKondisi) ?: 1;
                        $percentBaik = round(($unitKondisi['baik'] / $total) * 100);
                        $percentRingan = round(($unitKondisi['rusak_ringan'] / $total) * 100);
                        $percentBerat = round(($unitKondisi['rusak_berat'] / $total) * 100);
                        $percentMati = round(($unitKondisi['mati_total'] / $total) * 100);
                    @endphp
                    <svg viewBox="0 0 36 36" class="w-full h-full">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        {{-- Baik --}}
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#22c55e" stroke-width="3"
                                stroke-dasharray="{{ $percentBaik }} {{ 100 - $percentBaik }}"
                                stroke-dashoffset="25" class="transition-all"/>
                        {{-- Rusak Ringan --}}
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#eab308" stroke-width="3"
                                stroke-dasharray="{{ $percentRingan }} {{ 100 - $percentRingan }}"
                                stroke-dashoffset="{{ 25 - $percentBaik }}" class="transition-all"/>
                        {{-- Rusak Berat --}}
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f97316" stroke-width="3"
                                stroke-dasharray="{{ $percentBerat }} {{ 100 - $percentBerat }}"
                                stroke-dashoffset="{{ 25 - $percentBaik - $percentRingan }}" class="transition-all"/>
                        {{-- Mati Total --}}
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#ef4444" stroke-width="3"
                                stroke-dasharray="{{ $percentMati }} {{ 100 - $percentMati }}"
                                stroke-dashoffset="{{ 25 - $percentBaik - $percentRingan - $percentBerat }}" class="transition-all"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ array_sum($unitKondisi) }}</p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>
                {{-- Legend --}}
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="text-sm text-gray-600">Baik</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $unitKondisi['baik'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            <span class="text-sm text-gray-600">Rusak Ringan</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $unitKondisi['rusak_ringan'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                            <span class="text-sm text-gray-600">Rusak Berat</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $unitKondisi['rusak_berat'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-sm text-gray-600">Mati Total</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $unitKondisi['mati_total'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lab Stats --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status per Laboratorium</h2>
            <div class="space-y-3">
                @forelse ($labStats as $lab)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                        <div>
                            <p class="font-medium text-gray-900">{{ $lab->nama }}</p>
                            <p class="text-xs text-gray-500">{{ $lab->total_units }} unit</p>
                        </div>
                        @if ($lab->bermasalah > 0)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                {{ $lab->bermasalah }} bermasalah
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Semua baik
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada data laboratorium.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection