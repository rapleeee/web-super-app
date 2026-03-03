@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.data-sarana.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Sarana Umum</h1>
            <p class="text-gray-600 mt-1">Input sarana umum dan lokasi penempatannya</p>
        </div>
    </div>

    <x-form-draft formId="create-sarana-umum" formName="Sarana Umum">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="create-sarana-umum" action="{{ route('sarana-umum.data-sarana.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_inventaris" class="block text-sm font-medium text-gray-700 mb-2">Kode Inventaris <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_inventaris" id="kode_inventaris" value="{{ old('kode_inventaris') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kode_inventaris') border-red-500 @enderror"
                               placeholder="Contoh: SRN-001">
                        @error('kode_inventaris')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Sarana <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nama') border-red-500 @enderror"
                               placeholder="Contoh: Proyektor Epson EB-X06">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Sarana <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis" id="jenis" value="{{ old('jenis') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('jenis') border-red-500 @enderror"
                               placeholder="Contoh: AC, CCTV, Sound, Proyektor">
                        @error('jenis')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Penempatan <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('lokasi') border-red-500 @enderror"
                               placeholder="Contoh: Aula Utama / Koridor Lt.2">
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="merk" class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                        <input type="text" name="merk" id="merk" value="{{ old('merk') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('merk') border-red-500 @enderror">
                        @error('merk')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nomor_seri" class="block text-sm font-medium text-gray-700 mb-2">Nomor Seri</label>
                        <input type="text" name="nomor_seri" id="nomor_seri" value="{{ old('nomor_seri') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nomor_seri') border-red-500 @enderror">
                        @error('nomor_seri')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="tahun_pengadaan" class="block text-sm font-medium text-gray-700 mb-2">Tahun Pengadaan</label>
                        <input type="number" name="tahun_pengadaan" id="tahun_pengadaan" value="{{ old('tahun_pengadaan') }}" min="2000" max="2100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tahun_pengadaan') border-red-500 @enderror">
                        @error('tahun_pengadaan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">Kondisi <span class="text-red-500">*</span></label>
                        <select name="kondisi" id="kondisi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi') border-red-500 @enderror">
                            <option value="baik" {{ old('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="mati_total" {{ old('kondisi') === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                        </select>
                        @error('kondisi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="dalam_perbaikan" {{ old('status') === 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="tidak_aktif" {{ old('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Sarana</label>
                    <input type="file" name="foto" id="foto" accept="image/*" data-max-kb="2048" data-file-label="Foto Sarana Umum"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('foto') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG. Maksimal 2MB.</p>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('sarana-umum.data-sarana.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">Simpan</button>
            </div>
        </form>
    </div>
    </x-form-draft>
</div>
@endsection
