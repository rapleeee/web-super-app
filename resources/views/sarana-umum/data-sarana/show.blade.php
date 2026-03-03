@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.data-sarana.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Sarana Umum</h1>
            <p class="text-gray-600 mt-1">{{ $saranaUmum->nama }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-[#272125] px-6 py-8 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-white/10 flex items-center justify-center mb-4">
                    <x-heroicon-o-building-office class="w-10 h-10 text-white"/>
                </div>
                <h2 class="text-xl font-semibold text-white">{{ $saranaUmum->nama }}</h2>
                <p class="text-white/70">{{ $saranaUmum->kode_inventaris }}</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Jenis</p>
                    <p class="text-gray-900 font-medium">{{ $saranaUmum->jenis }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Lokasi</p>
                    <p class="text-gray-900 font-medium">{{ $saranaUmum->lokasi }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $saranaUmum->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $saranaUmum->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $saranaUmum->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ str_replace('_', ' ', ucfirst($saranaUmum->status)) }}
                    </span>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-end gap-3">
                <a href="{{ route('sarana-umum.data-sarana.qr', $saranaUmum) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                    <x-heroicon-o-qr-code class="w-4 h-4"/>
                    QR Code
                </a>
                <a href="{{ route('sarana-umum.data-sarana.edit', $saranaUmum) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    <x-heroicon-o-pencil-square class="w-4 h-4"/>
                    Edit
                </a>
                <form id="delete-sarana-show" action="{{ route('sarana-umum.data-sarana.destroy', $saranaUmum) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('delete-sarana-show', 'Hapus Sarana?', 'Data {{ $saranaUmum->nama }} akan dihapus permanen!')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <x-heroicon-o-trash class="w-4 h-4"/>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Merk</p>
                    <p class="text-gray-900">{{ $saranaUmum->merk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Model</p>
                    <p class="text-gray-900">{{ $saranaUmum->model ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Seri</p>
                    <p class="text-gray-900">{{ $saranaUmum->nomor_seri ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tahun Pengadaan</p>
                    <p class="text-gray-900">{{ $saranaUmum->tahun_pengadaan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Kondisi</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $saranaUmum->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $saranaUmum->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $saranaUmum->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $saranaUmum->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ str_replace('_', ' ', ucfirst($saranaUmum->kondisi)) }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Keterangan</p>
                <p class="text-gray-900">{{ $saranaUmum->keterangan ?: '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-2">Foto</p>
                @if ($saranaUmum->foto)
                    <img src="{{ Storage::url($saranaUmum->foto) }}" alt="Foto {{ $saranaUmum->nama }}" class="w-full max-w-md rounded-lg border object-cover">
                @else
                    <p class="text-gray-500">Belum ada foto.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
