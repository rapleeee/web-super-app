@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.berita-acara.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Berita Acara</h1>
            <p class="text-gray-600 mt-1">Perbarui data penggunaan sarana umum</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('sarana-umum.berita-acara.update', $beritaAcara) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="sarana_umum_id" class="block text-sm font-medium text-gray-700 mb-2">Sarana Umum <span class="text-red-500">*</span></label>
                    <select name="sarana_umum_id" id="sarana_umum_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('sarana_umum_id') border-red-500 @enderror">
                        @foreach ($saranaUmums as $sarana)
                            <option value="{{ $sarana->id }}" {{ old('sarana_umum_id', $beritaAcara->sarana_umum_id) == $sarana->id ? 'selected' : '' }}>{{ $sarana->nama }} ({{ $sarana->kode_inventaris }})</option>
                        @endforeach
                    </select>
                    @error('sarana_umum_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                    <select name="ruangan_id" id="ruangan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('ruangan_id') border-red-500 @enderror">
                        <option value="">Pilih Ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $beritaAcara->ruangan_id) == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama }} ({{ $ruangan->kode }})</option>
                        @endforeach
                    </select>
                    @error('ruangan_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $beritaAcara->tanggal->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai', \Carbon\Carbon::parse($beritaAcara->waktu_mulai)->format('H:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('waktu_mulai') border-red-500 @enderror">
                        @error('waktu_mulai')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai', \Carbon\Carbon::parse($beritaAcara->waktu_selesai)->format('H:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="nama_guru" class="block text-sm font-medium text-gray-700 mb-2">Nama Guru <span class="text-red-500">*</span></label>
                    <select name="nama_guru" id="nama_guru" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('nama_guru') border-red-500 @enderror">
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->nama }}" {{ old('nama_guru', $beritaAcara->nama_guru) === $guru->nama ? 'selected' : '' }}>{{ $guru->nama }}{{ $guru->nip ? ' (' . $guru->nip . ')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('nama_guru')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" id="mata_pelajaran" value="{{ old('mata_pelajaran', $beritaAcara->mata_pelajaran) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('mata_pelajaran') border-red-500 @enderror">
                    @error('mata_pelajaran')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $beritaAcara->kelas) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('kelas') border-red-500 @enderror">
                    @error('kelas')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Peserta <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_peserta" id="jumlah_peserta" value="{{ old('jumlah_peserta', $beritaAcara->jumlah_peserta) }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('jumlah_peserta') border-red-500 @enderror">
                    @error('jumlah_peserta')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('status') border-red-500 @enderror">
                        <option value="draft" {{ old('status', $beritaAcara->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="final" {{ old('status', $beritaAcara->status) === 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">Kegiatan</label>
                <textarea name="kegiatan" id="kegiatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('kegiatan') border-red-500 @enderror">{{ old('kegiatan', $beritaAcara->kegiatan) }}</textarea>
                @error('kegiatan')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" id="catatan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('catatan') border-red-500 @enderror">{{ old('catatan', $beritaAcara->catatan) }}</textarea>
                @error('catatan')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('sarana-umum.berita-acara.show', $beritaAcara) }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">Batal</a>
                <button type="submit" class="px-6 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
