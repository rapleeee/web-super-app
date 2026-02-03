@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.komponen-perangkat.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Komponen Perangkat</h1>
            <p class="text-gray-600 mt-1">{{ $komponenPerangkat->kode_inventaris }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('laboran.komponen-perangkat.update', $komponenPerangkat) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Unit Komputer --}}
                <div>
                    <label for="unit_komputer_id" class="block text-sm font-medium text-gray-700 mb-2">Unit Komputer <span class="text-red-500">*</span></label>
                    <select name="unit_komputer_id" id="unit_komputer_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('unit_komputer_id') border-red-500 @enderror">
                        <option value="">Pilih Unit Komputer</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_komputer_id', $komponenPerangkat->unit_komputer_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }} - {{ $unit->laboratorium->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_komputer_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Perangkat <span class="text-red-500">*</span></label>
                    <select name="kategori_id" id="kategori_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kategori_id') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $komponenPerangkat->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode Inventaris --}}
                <div>
                    <label for="kode_inventaris" class="block text-sm font-medium text-gray-700 mb-2">Kode Inventaris <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_inventaris" id="kode_inventaris" value="{{ old('kode_inventaris', $komponenPerangkat->kode_inventaris) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kode_inventaris') border-red-500 @enderror">
                    @error('kode_inventaris')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Merk --}}
                    <div>
                        <label for="merk" class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                        <input type="text" name="merk" id="merk" value="{{ old('merk', $komponenPerangkat->merk) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('merk') border-red-500 @enderror">
                        @error('merk')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Model --}}
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $komponenPerangkat->model) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Nomor Seri --}}
                    <div>
                        <label for="nomor_seri" class="block text-sm font-medium text-gray-700 mb-2">Nomor Seri</label>
                        <input type="text" name="nomor_seri" id="nomor_seri" value="{{ old('nomor_seri', $komponenPerangkat->nomor_seri) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nomor_seri') border-red-500 @enderror">
                        @error('nomor_seri')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Pengadaan --}}
                    <div>
                        <label for="tahun_pengadaan" class="block text-sm font-medium text-gray-700 mb-2">Tahun Pengadaan</label>
                        <input type="number" name="tahun_pengadaan" id="tahun_pengadaan" value="{{ old('tahun_pengadaan', $komponenPerangkat->tahun_pengadaan) }}" min="2000" max="{{ date('Y') + 1 }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tahun_pengadaan') border-red-500 @enderror">
                        @error('tahun_pengadaan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Kondisi --}}
                    <div>
                        <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">Kondisi <span class="text-red-500">*</span></label>
                        <select name="kondisi" id="kondisi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi') border-red-500 @enderror">
                            <option value="baik" {{ old('kondisi', $komponenPerangkat->kondisi) === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi', $komponenPerangkat->kondisi) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi', $komponenPerangkat->kondisi) === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="mati_total" {{ old('kondisi', $komponenPerangkat->kondisi) === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                        </select>
                        @error('kondisi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="aktif" {{ old('status', $komponenPerangkat->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="dalam_perbaikan" {{ old('status', $komponenPerangkat->status) === 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="tidak_aktif" {{ old('status', $komponenPerangkat->status) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div>
                    <label for="spesifikasi" class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi</label>
                    <textarea name="spesifikasi" id="spesifikasi" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('spesifikasi') border-red-500 @enderror">{{ old('spesifikasi', $komponenPerangkat->spesifikasi) }}</textarea>
                    @error('spesifikasi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $komponenPerangkat->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                    @if($komponenPerangkat->foto)
                        <div class="mb-3">
                            <img src="{{ Storage::url($komponenPerangkat->foto) }}" alt="Foto komponen" class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('foto') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maks: 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('laboran.komponen-perangkat.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
