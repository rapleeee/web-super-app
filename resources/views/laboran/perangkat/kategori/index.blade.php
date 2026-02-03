@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kategori Perangkat</h1>
            <p class="text-gray-600 mt-1">Kelola kategori perangkat laboratorium</p>
        </div>
        <a href="{{ route('laboran.kategori-perangkat.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Kategori
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
                        <th class="px-6 py-4 text-left">Deskripsi</th>
                        <th class="px-6 py-4 text-center">Jumlah Komponen</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($kategoriPerangkats as $kategori)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $kategori->kode }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#272125] flex items-center justify-center text-white">
                                        @if($kategori->icon)
                                            <x-dynamic-component :component="'heroicon-o-' . $kategori->icon" class="w-5 h-5"/>
                                        @else
                                            <x-heroicon-o-cube class="w-5 h-5"/>
                                        @endif
                                    </div>
                                    <span class="font-medium">{{ $kategori->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ Str::limit($kategori->deskripsi, 50) ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $kategori->komponen_perangkats_count }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kategori->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($kategori->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.kategori-perangkat.show', $kategori) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.kategori-perangkat.edit', $kategori) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-kategori-{{ $kategori->id }}" action="{{ route('laboran.kategori-perangkat.destroy', $kategori) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-kategori-{{ $kategori->id }}', 'Hapus Kategori?', 'Data {{ $kategori->nama }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-cube class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data kategori perangkat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kategoriPerangkats->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $kategoriPerangkats->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
