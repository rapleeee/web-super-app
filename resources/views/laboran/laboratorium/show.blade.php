@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.laboratorium.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Laboratorium</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap laboratorium</p>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Header with Photo --}}
        <div class="relative h-48 bg-[#272125]">
            @if ($laboratorium->foto)
                <img src="{{ Storage::url($laboratorium->foto) }}" alt="{{ $laboratorium->nama }}" class="w-full h-full object-cover opacity-50">
            @endif
            <div class="absolute inset-0 flex items-end p-6">
                <div class="text-white">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-1 rounded bg-white/20">{{ $laboratorium->kode }}</span>
                        <span class="text-xs px-2 py-1 rounded
                            {{ $laboratorium->jurusan === 'RPL' ? 'bg-blue-500' : '' }}
                            {{ $laboratorium->jurusan === 'TKJ' ? 'bg-purple-500' : '' }}
                            {{ $laboratorium->jurusan === 'DKV' ? 'bg-orange-500' : '' }}">
                            {{ $laboratorium->jurusan }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold">{{ $laboratorium->nama }}</h2>
                    <p class="text-white/70">{{ $laboratorium->lokasi }}</p>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-6 space-y-6">
            {{-- Status & Kapasitas --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $laboratorium->kapasitas }}</p>
                    <p class="text-sm text-gray-500">Kapasitas</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $laboratorium->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $laboratorium->status === 'nonaktif' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $laboratorium->status === 'renovasi' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ ucfirst($laboratorium->status) }}
                    </span>
                    <p class="text-sm text-gray-500 mt-1">Status</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center col-span-2">
                    <p class="font-medium text-gray-900">{{ $laboratorium->penanggungJawab?->nama ?? '-' }}</p>
                    <p class="text-sm text-gray-500">Penanggung Jawab</p>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if ($laboratorium->deskripsi)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Deskripsi</p>
                    <p class="text-gray-700">{{ $laboratorium->deskripsi }}</p>
                </div>
            @endif

            {{-- Fasilitas --}}
            @if ($laboratorium->fasilitas && count($laboratorium->fasilitas) > 0)
                <div>
                    <p class="text-sm text-gray-500 mb-3">Fasilitas Pendukung</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($laboratorium->fasilitas as $fasilitas)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                <x-heroicon-o-check-circle class="w-4 h-4 text-green-500"/>
                                {{ $fasilitas }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Timestamps --}}
            <div class="grid grid-cols-2 gap-4 pt-4 border-t text-sm">
                <div>
                    <p class="text-gray-500">Dibuat pada</p>
                    <p class="font-medium text-gray-900">{{ $laboratorium->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Terakhir diperbarui</p>
                    <p class="font-medium text-gray-900">{{ $laboratorium->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-end gap-3">
            <a href="{{ route('laboran.laboratorium.edit', $laboratorium) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                <x-heroicon-o-pencil-square class="w-4 h-4"/>
                Edit
            </a>
            <form id="delete-lab-show" action="{{ route('laboran.laboratorium.destroy', $laboratorium) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-lab-show', 'Hapus Laboratorium?', 'Data {{ $laboratorium->nama }} akan dihapus permanen!')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-4 h-4"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
