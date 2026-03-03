@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('sarana-umum.berita-acara.index') }}" class="text-gray-500 hover:text-gray-700">
                <x-heroicon-o-arrow-left class="w-6 h-6"/>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Berita Acara</h1>
                <p class="text-gray-600 mt-1">{{ $beritaAcara->tanggal->format('d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('sarana-umum.berita-acara.edit', $beritaAcara) }}" class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                Edit
            </a>
            <form id="delete-ba" action="{{ route('sarana-umum.berita-acara.destroy', $beritaAcara) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-ba', 'Hapus Berita Acara?', 'Data akan dihapus permanen!')" class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    <x-heroicon-o-trash class="w-5 h-5"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $beritaAcara->status === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
        {{ ucfirst($beritaAcara->status) }}
    </span>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Penggunaan</h2>
            <p><span class="text-sm text-gray-500">Sarana:</span> <span class="text-gray-900">{{ $beritaAcara->saranaUmum?->nama ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Ruangan:</span> <span class="text-gray-900">{{ $beritaAcara->ruangan?->nama ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Waktu:</span> <span class="text-gray-900">{{ \Carbon\Carbon::parse($beritaAcara->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($beritaAcara->waktu_selesai)->format('H:i') }} WIB</span></p>
            <p><span class="text-sm text-gray-500">Kelas:</span> <span class="text-gray-900">{{ $beritaAcara->kelas }}</span></p>
            <p><span class="text-sm text-gray-500">Jumlah Peserta:</span> <span class="text-gray-900">{{ $beritaAcara->jumlah_peserta }}</span></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Pengajar</h2>
            <p><span class="text-sm text-gray-500">Nama Guru:</span> <span class="text-gray-900">{{ $beritaAcara->nama_guru }}</span></p>
            <p><span class="text-sm text-gray-500">Mata Pelajaran:</span> <span class="text-gray-900">{{ $beritaAcara->mata_pelajaran ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Dicatat oleh:</span> <span class="text-gray-900">{{ $beritaAcara->user?->name ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Diperbarui:</span> <span class="text-gray-900">{{ $beritaAcara->updated_at->format('d/m/Y H:i') }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Kegiatan</p>
            <p class="text-gray-700 whitespace-pre-line">{{ $beritaAcara->kegiatan ?: '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Catatan</p>
            <p class="text-gray-700 whitespace-pre-line">{{ $beritaAcara->catatan ?: '-' }}</p>
        </div>
    </div>
</div>
@endsection
