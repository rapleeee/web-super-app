@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Guru</h1>
            <p class="text-gray-600 mt-1">Kelola data guru pengajar</p>
        </div>
        <a href="{{ route('laboran.data-master.guru.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Guru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('laboran.data-master.guru.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..."
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
                <a href="{{ route('laboran.data-master.guru.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
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
                        <th class="px-6 py-4 text-left">NIP</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">No. Telepon</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($gurus as $guru)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $guru->nip ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#BFB07C] flex items-center justify-center text-[#272125] font-semibold text-xs">
                                        {{ strtoupper(substr($guru->nama, 0, 2)) }}
                                    </div>
                                    <span>{{ $guru->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $guru->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $guru->no_telepon ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $guru->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($guru->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.data-master.guru.edit', $guru) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-guru-{{ $guru->id }}" action="{{ route('laboran.data-master.guru.destroy', $guru) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-guru-{{ $guru->id }}', 'Hapus Guru?', 'Data {{ $guru->nama }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-academic-cap class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data guru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($gurus->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
