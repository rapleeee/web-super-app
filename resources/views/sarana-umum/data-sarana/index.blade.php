@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sarana Umum</h1>
            <p class="text-gray-600 mt-1">Kelola sarana umum sekolah seperti AC, proyektor, CCTV, dan sound system.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('sarana-umum.data-sarana.template') }}"
               class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                <x-heroicon-o-document-arrow-down class="w-5 h-5"/>
                Template CSV
            </a>
            <a href="{{ route('sarana-umum.data-sarana.import') }}"
               class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                <x-heroicon-o-arrow-up-tray class="w-5 h-5"/>
                Import CSV
            </a>
            <a href="{{ route('sarana-umum.data-sarana.create') }}"
               class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
                <x-heroicon-o-plus class="w-5 h-5"/>
                Tambah Sarana
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('sarana-umum.data-sarana.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[220px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau lokasi..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
            </div>
            <div class="min-w-[180px]">
                <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Jenis</option>
                    @foreach ($jenisOptions as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[170px]">
                <select name="kondisi" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="mati_total" {{ request('kondisi') === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                </select>
            </div>
            <div class="min-w-[170px]">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="dalam_perbaikan" {{ request('status') === 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                    <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">Filter</button>
            @if(request()->hasAny(['search', 'jenis', 'kondisi', 'status']))
                <a href="{{ route('sarana-umum.data-sarana.index') }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Kode</th>
                        <th class="px-6 py-4 text-left">Nama Sarana</th>
                        <th class="px-6 py-4 text-left">Jenis</th>
                        <th class="px-6 py-4 text-left">Lokasi</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($saranaUmums as $sarana)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $sarana->kode_inventaris }}</td>
                            <td class="px-6 py-4">{{ $sarana->nama }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $sarana->jenis }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $sarana->lokasi }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $sarana->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $sarana->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $sarana->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $sarana->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($sarana->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $sarana->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $sarana->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $sarana->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($sarana->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('sarana-umum.data-sarana.show', $sarana) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('sarana-umum.data-sarana.qr', $sarana) }}" class="text-indigo-600 hover:text-indigo-800" title="QR">
                                        <x-heroicon-o-qr-code class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('sarana-umum.data-sarana.edit', $sarana) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-sarana-{{ $sarana->id }}" action="{{ route('sarana-umum.data-sarana.destroy', $sarana) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-sarana-{{ $sarana->id }}', 'Hapus Sarana?', 'Data {{ $sarana->nama }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-building-office class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data sarana umum.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($saranaUmums->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $saranaUmums->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
