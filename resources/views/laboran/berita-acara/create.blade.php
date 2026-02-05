@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.berita-acara.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Berita Acara</h1>
            <p class="text-gray-600 mt-1">Catat penggunaan laboratorium</p>
        </div>
    </div>

    {{-- Form with Draft --}}
    <x-form-draft formId="create-berita-acara" formName="Berita Acara">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="create-berita-acara" action="{{ route('laboran.berita-acara.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Laboratorium & Tanggal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="laboratorium_id" class="block text-sm font-medium text-gray-700 mb-2">Laboratorium <span class="text-red-500">*</span></label>
                    <select name="laboratorium_id" id="laboratorium_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('laboratorium_id') border-red-500 @enderror">
                        <option value="">Pilih Laboratorium</option>
                        @foreach ($laboratoriums as $lab)
                            <option value="{{ $lab->id }}" {{ old('laboratorium_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama }} ({{ $lab->kode }})
                            </option>
                        @endforeach
                    </select>
                    @error('laboratorium_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Waktu --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('waktu_mulai') border-red-500 @enderror">
                    @error('waktu_mulai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('waktu_selesai') border-red-500 @enderror">
                    @error('waktu_selesai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Guru & Mapel --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="nama_guru" class="block text-sm font-medium text-gray-700 mb-2">Nama Guru <span class="text-red-500">*</span></label>
                    <select name="nama_guru" id="nama_guru"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nama_guru') border-red-500 @enderror">
                        <option value="">Pilih Guru</option>
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->nama }}" {{ old('nama_guru') === $guru->nama ? 'selected' : '' }}>
                                {{ $guru->nama }}{{ $guru->nip ? ' (' . $guru->nip . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('nama_guru')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <a href="{{ route('laboran.data-master.guru.create') }}" class="text-blue-600 hover:underline">+ Tambah guru baru</a>
                    </p>
                </div>

                <div>
                    <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <select name="mata_pelajaran" id="mata_pelajaran"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('mata_pelajaran') border-red-500 @enderror">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajarans as $mapel)
                            <option value="{{ $mapel->nama }}" {{ old('mata_pelajaran') === $mapel->nama ? 'selected' : '' }}>
                                {{ $mapel->nama }}{{ $mapel->kode ? ' (' . $mapel->kode . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <a href="{{ route('laboran.data-master.mata-pelajaran.create') }}" class="text-blue-600 hover:underline">+ Tambah mata pelajaran baru</a>
                    </p>
                </div>
            </div>

            {{-- Kelas & Jumlah --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas" id="kelas"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kelas') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelass as $kls)
                            <option value="{{ $kls->nama_lengkap }}" {{ old('kelas') === $kls->nama_lengkap ? 'selected' : '' }}>
                                {{ $kls->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <a href="{{ route('laboran.data-master.kelas.create') }}" class="text-blue-600 hover:underline">+ Tambah kelas baru</a>
                    </p>
                </div>
                    @error('kelas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah_siswa" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Siswa <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_siswa" id="jumlah_siswa" value="{{ old('jumlah_siswa') }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('jumlah_siswa') border-red-500 @enderror"
                           placeholder="30">
                    @error('jumlah_siswa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah_pc_digunakan" class="block text-sm font-medium text-gray-700 mb-2">Jumlah PC Digunakan <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_pc_digunakan" id="jumlah_pc_digunakan" value="{{ old('jumlah_pc_digunakan') }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('jumlah_pc_digunakan') border-red-500 @enderror"
                           placeholder="15">
                    @error('jumlah_pc_digunakan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alat Tambahan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alat Tambahan yang Digunakan</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $oldAlatTambahan = old('alat_tambahan', []);
                    @endphp
                    @foreach ($alatTambahanOptions as $alat)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="alat_tambahan[]" value="{{ $alat }}"
                                   class="rounded border-gray-300 text-[#272125] focus:ring-[#272125]"
                                   {{ in_array($alat, $oldAlatTambahan) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $alat }}</span>
                        </label>
                    @endforeach
                </div>
                @error('alat_tambahan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kegiatan --}}
            <div>
                <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">Kegiatan/Materi</label>
                <textarea name="kegiatan" id="kegiatan" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kegiatan') border-red-500 @enderror"
                          placeholder="Deskripsi kegiatan atau materi yang diajarkan...">{{ old('kegiatan') }}</textarea>
                @error('kegiatan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catatan --}}
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" id="catatan" rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('catatan') border-red-500 @enderror"
                          placeholder="Catatan tambahan jika ada...">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="final" {{ old('status') === 'final' ? 'selected' : '' }}>Final</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('laboran.berita-acara.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
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
