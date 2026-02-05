@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('laboran.berita-acara.index') }}" class="text-gray-500 hover:text-gray-700">
                <x-heroicon-o-arrow-left class="w-6 h-6"/>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Berita Acara</h1>
                <p class="text-gray-600 mt-1">{{ $beritaAcara->tanggal->format('d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('laboran.berita-acara.edit', $beritaAcara) }}"
               class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                Edit
            </a>
            <form id="delete-ba" action="{{ route('laboran.berita-acara.destroy', $beritaAcara) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-ba', 'Hapus Berita Acara?', 'Data akan dihapus permanen!')"
                        class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    <x-heroicon-o-trash class="w-5 h-5"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Status Badge --}}
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            {{ $beritaAcara->status === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            <x-heroicon-o-check-circle class="w-4 h-4 mr-1"/>
            {{ ucfirst($beritaAcara->status) }}
        </span>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Informasi Umum --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-gray-500"/>
                    Informasi Umum
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Laboratorium</p>
                        <p class="font-medium">{{ $beritaAcara->laboratorium->nama }}</p>
                        <p class="text-xs text-gray-400">{{ $beritaAcara->laboratorium->kode }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="font-medium">{{ $beritaAcara->tanggal->format('l, d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Waktu</p>
                        <p class="font-medium">
                            {{ \Carbon\Carbon::parse($beritaAcara->waktu_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($beritaAcara->waktu_selesai)->format('H:i') }} WIB
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dicatat oleh</p>
                        <p class="font-medium">{{ $beritaAcara->user?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Kelas --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-academic-cap class="w-5 h-5 text-gray-500"/>
                    Informasi Kelas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Guru</p>
                        <p class="font-medium">{{ $beritaAcara->nama_guru }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Mata Pelajaran</p>
                        <p class="font-medium">{{ $beritaAcara->mata_pelajaran ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kelas</p>
                        <p class="font-medium">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $beritaAcara->kelas }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Siswa</p>
                        <p class="font-medium">{{ $beritaAcara->jumlah_siswa }} siswa</p>
                    </div>
                </div>
            </div>

            {{-- Kegiatan --}}
            @if ($beritaAcara->kegiatan)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-gray-500"/>
                    Kegiatan/Materi
                </h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $beritaAcara->kegiatan }}</p>
            </div>
            @endif

            {{-- Catatan --}}
            @if ($beritaAcara->catatan)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5 text-gray-500"/>
                    Catatan
                </h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $beritaAcara->catatan }}</p>
            </div>
            @endif
        </div>

        {{-- Right Column - Equipment --}}
        <div class="space-y-6">
            {{-- Perangkat Digunakan --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-computer-desktop class="w-5 h-5 text-gray-500"/>
                    Perangkat
                </h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">PC Digunakan</span>
                        <span class="font-semibold text-lg">{{ $beritaAcara->jumlah_pc_digunakan }}</span>
                    </div>
                </div>
            </div>

            {{-- Alat Tambahan --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-gray-500"/>
                    Alat Tambahan
                </h2>
                @if ($beritaAcara->alat_tambahan && count($beritaAcara->alat_tambahan) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($beritaAcara->alat_tambahan as $alat)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                @switch($alat)
                                    @case('Kamera')
                                        <x-heroicon-o-camera class="w-4 h-4 mr-1"/>
                                        @break
                                    @case('Headset')
                                        <x-heroicon-o-speaker-wave class="w-4 h-4 mr-1"/>
                                        @break
                                    @case('Proyektor')
                                        <x-heroicon-o-tv class="w-4 h-4 mr-1"/>
                                        @break
                                    @case('Sound')
                                        <x-heroicon-o-musical-note class="w-4 h-4 mr-1"/>
                                        @break
                                    @default
                                        <x-heroicon-o-cube class="w-4 h-4 mr-1"/>
                                @endswitch
                                {{ $alat }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Tidak ada alat tambahan yang digunakan.</p>
                @endif
            </div>

            {{-- Timestamps --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-clock class="w-5 h-5 text-gray-500"/>
                    Riwayat
                </h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat</span>
                        <span class="text-gray-700">{{ $beritaAcara->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diperbarui</span>
                        <span class="text-gray-700">{{ $beritaAcara->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
