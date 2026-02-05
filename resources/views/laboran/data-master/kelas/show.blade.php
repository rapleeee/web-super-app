@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.data-master.kelas.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kelas</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap kelas</p>
        </div>
    </div>

    {{-- Detail --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start gap-6">
            <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xl">
                {{ $kelas->tingkat }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ $kelas->nama_lengkap }}</h2>
                <p class="text-gray-500 mt-1">{{ $kelas->jurusan }} - Rombel {{ $kelas->rombel }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $kelas->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($kelas->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tingkat</h3>
                <p class="mt-1 text-gray-900 text-lg font-semibold">Kelas {{ $kelas->tingkat }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Jurusan</h3>
                @php
                    $jurusanFull = [
                        'RPL' => 'Rekayasa Perangkat Lunak',
                        'DKV' => 'Desain Komunikasi Visual',
                        'TKJ' => 'Teknik Komputer dan Jaringan',
                    ];
                @endphp
                <p class="mt-1 text-gray-900 text-lg font-semibold">{{ $kelas->jurusan }}</p>
                <p class="text-sm text-gray-500">{{ $jurusanFull[$kelas->jurusan] ?? '' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Rombel</h3>
                <p class="mt-1 text-gray-900 text-lg font-semibold">{{ $kelas->rombel }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</h3>
                <p class="mt-1 text-gray-900">{{ $kelas->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Terakhir Diperbarui</h3>
                <p class="mt-1 text-gray-900">{{ $kelas->updated_at->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
            <a href="{{ route('laboran.data-master.kelas.edit', $kelas) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                Edit
            </a>
            <form id="delete-kelas-{{ $kelas->id }}" action="{{ route('laboran.data-master.kelas.destroy', $kelas) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-kelas-{{ $kelas->id }}', 'Hapus Kelas?', 'Data {{ $kelas->nama_lengkap }} akan dihapus permanen!')" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-5 h-5"/>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
