@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.data-master.mata-pelajaran.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Mata Pelajaran</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap mata pelajaran</p>
        </div>
    </div>

    {{-- Detail --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start gap-6">
            <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                <x-heroicon-o-book-open class="w-8 h-8"/>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ $mataPelajaran->nama }}</h2>
                <p class="text-gray-500 mt-1">Kode: {{ $mataPelajaran->kode ?? '-' }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $mataPelajaran->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($mataPelajaran->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Deskripsi</h3>
                <p class="mt-1 text-gray-900">{{ $mataPelajaran->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</h3>
                <p class="mt-1 text-gray-900">{{ $mataPelajaran->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Terakhir Diperbarui</h3>
                <p class="mt-1 text-gray-900">{{ $mataPelajaran->updated_at->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
            <a href="{{ route('laboran.data-master.mata-pelajaran.edit', $mataPelajaran) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                Edit
            </a>
            <form id="delete-mapel-{{ $mataPelajaran->id }}" action="{{ route('laboran.data-master.mata-pelajaran.destroy', $mataPelajaran) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-mapel-{{ $mataPelajaran->id }}', 'Hapus Mata Pelajaran?', 'Data {{ $mataPelajaran->nama }} akan dihapus permanen!')" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-5 h-5"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
