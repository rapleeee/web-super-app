@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.laboratorium.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Laboratorium</h1>
            <p class="text-gray-600 mt-1">Isi data laboratorium baru</p>
        </div>
    </div>

    {{-- Form with Draft --}}
    <x-form-draft formId="create-laboratorium" formName="Laboratorium">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="create-laboratorium" action="{{ route('laboran.laboratorium.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Kode --}}
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" id="kode" value="{{ old('kode') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kode') @enderror"
                           placeholder="LAB-RPL-01">
                    @error('kode')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Laboratorium <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nama') @enderror"
                           placeholder="Laboratorium RPL 1">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Lokasi --}}
            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('lokasi') border-red-500 @enderror"
                       placeholder="Gedung A Lt. 2">
                @error('lokasi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {{-- Kapasitas --}}
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kapasitas') border-red-500 @enderror"
                           placeholder="30">
                    @error('kapasitas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jurusan --}}
                <div>
                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                    <select name="jurusan" id="jurusan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('jurusan') border-red-500 @enderror">
                        <option value="">Pilih Jurusan</option>
                        <option value="RPL" {{ old('jurusan') === 'RPL' ? 'selected' : '' }}>RPL</option>
                        <option value="TKJ" {{ old('jurusan') === 'TKJ' ? 'selected' : '' }}>TKJ</option>
                        <option value="DKV" {{ old('jurusan') === 'DKV' ? 'selected' : '' }}>DKV</option>
                    </select>
                    @error('jurusan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="renovasi" {{ old('status') === 'renovasi' ? 'selected' : '' }}>Renovasi</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Penanggung Jawab --}}
            <div>
                <label for="penanggung_jawab_id" class="block text-sm font-medium text-gray-700 mb-2">Penanggung Jawab</label>
                <select name="penanggung_jawab_id" id="penanggung_jawab_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('penanggung_jawab_id') border-red-500 @enderror">
                    <option value="">-- Pilih Penanggung Jawab --</option>
                    @foreach ($petugasLaboran as $petugas)
                        <option value="{{ $petugas->id }}" {{ old('penanggung_jawab_id') == $petugas->id ? 'selected' : '' }}>
                            {{ $petugas->nama }} ({{ $petugas->nip }})
                        </option>
                    @endforeach
                </select>
                @error('penanggung_jawab_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsi laboratorium...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fasilitas --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Pendukung</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $fasilitasList = ['AC', 'Proyektor', 'Whiteboard', 'Sound System', 'CCTV', 'WiFi', 'Printer', 'Scanner'];
                        $oldFasilitas = old('fasilitas', []);
                    @endphp
                    @foreach ($fasilitasList as $fasilitas)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="fasilitas[]" value="{{ $fasilitas }}"
                                   class="rounded border-gray-300 text-[#272125] focus:ring-[#272125]"
                                   {{ in_array($fasilitas, $oldFasilitas) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $fasilitas }}</span>
                        </label>
                    @endforeach
                </div>
                @error('fasilitas')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto --}}
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Laboratorium</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('foto') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                @error('foto')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('laboran.laboratorium.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
    </x-form-draft>
</div>
@endsection
