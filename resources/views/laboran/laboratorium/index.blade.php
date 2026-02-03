@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Laboratorium</h1>
            <p class="text-gray-600 mt-1">Kelola data laboratorium</p>
        </div>
        <a href="{{ route('laboran.laboratorium.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Laboratorium
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Kode</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Lokasi</th>
                        <th class="px-6 py-4 text-center">Jurusan</th>
                        <th class="px-6 py-4 text-center">Kapasitas</th>
                        <th class="px-6 py-4 text-left">Penanggung Jawab</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($laboratoriums as $lab)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $lab->kode }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($lab->foto)
                                        <img src="{{ Storage::url($lab->foto) }}" alt="{{ $lab->nama }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-[#272125] flex items-center justify-center text-white font-semibold text-xs">
                                            <x-heroicon-o-building-office class="w-5 h-5"/>
                                        </div>
                                    @endif
                                    <span>{{ $lab->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $lab->lokasi }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $lab->jurusan === 'RPL' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $lab->jurusan === 'TKJ' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $lab->jurusan === 'DKV' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ $lab->jurusan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $lab->kapasitas }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $lab->penanggungJawab?->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $lab->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $lab->status === 'nonaktif' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $lab->status === 'renovasi' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($lab->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.laboratorium.show', $lab) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.laboratorium.edit', $lab) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-lab-{{ $lab->id }}" action="{{ route('laboran.laboratorium.destroy', $lab) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-lab-{{ $lab->id }}', 'Hapus Laboratorium?', 'Data {{ $lab->nama }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-building-office class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data laboratorium.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($laboratoriums->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $laboratoriums->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
