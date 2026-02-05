@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Mata Pelajaran</h1>
            <p class="text-gray-600 mt-1">Kelola data mata pelajaran</p>
        </div>
        <a href="{{ route('laboran.data-master.mata-pelajaran.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Mata Pelajaran
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('laboran.data-master.mata-pelajaran.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <x-heroicon-o-magnifying-glass class="w-5 h-5"/>
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('laboran.data-master.mata-pelajaran.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Kode</th>
                        <th class="px-6 py-4 text-left">Nama Mata Pelajaran</th>
                        <th class="px-6 py-4 text-left">Deskripsi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($mataPelajarans as $mapel)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $mapel->kode ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                        <x-heroicon-o-book-open class="w-5 h-5"/>
                                    </div>
                                    <span class="font-medium">{{ $mapel->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $mapel->deskripsi ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mapel->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($mapel->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.data-master.mata-pelajaran.edit', $mapel) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-mapel-{{ $mapel->id }}" action="{{ route('laboran.data-master.mata-pelajaran.destroy', $mapel) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-mapel-{{ $mapel->id }}', 'Hapus Mata Pelajaran?', 'Data {{ $mapel->nama }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-book-open class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data mata pelajaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mataPelajarans->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $mataPelajarans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
