@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.petugas.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Petugas Laboran</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap petugas laboran</p>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Header with Photo --}}
        <div class="bg-[#272125] px-6 py-8">
            <div class="flex items-center gap-4">
                @if ($petugas->foto)
                    <img src="{{ Storage::url($petugas->foto) }}" alt="{{ $petugas->nama }}" class="w-20 h-20 rounded-full object-cover border-4 border-white/20">
                @else
                    <div class="w-20 h-20 rounded-full bg-[#BFB07C] flex items-center justify-center text-[#272125] font-bold text-2xl border-4 border-white/20">
                        {{ strtoupper(substr($petugas->nama, 0, 2)) }}
                    </div>
                @endif
                <div class="text-white">
                    <h2 class="text-xl font-semibold">{{ $petugas->nama }}</h2>
                    <p class="text-white/70">{{ $petugas->nip }}</p>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-900">{{ $petugas->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">No. Telepon</p>
                    <p class="font-medium text-gray-900">{{ $petugas->no_telepon ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $petugas->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($petugas->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Terdaftar Sejak</p>
                    <p class="font-medium text-gray-900">{{ $petugas->created_at->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Laboratoriums Managed --}}
            @if ($petugas->laboratoriums->count() > 0)
                <div class="pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-3">Laboratorium yang Dikelola</p>
                    <div class="space-y-2">
                        @foreach ($petugas->laboratoriums as $lab)
                            <div class="flex items-center justify-between bg-gray-50 px-4 py-3 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $lab->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $lab->kode }} - {{ $lab->lokasi }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">{{ $lab->jurusan }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-end gap-3">
            <a href="{{ route('laboran.petugas.edit', $petugas) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                <x-heroicon-o-pencil-square class="w-4 h-4"/>
                Edit
            </a>
            <form id="delete-petugas-show" action="{{ route('laboran.petugas.destroy', $petugas) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-petugas-show', 'Hapus Petugas?', 'Data {{ $petugas->nama }} akan dihapus permanen!')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-4 h-4"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
