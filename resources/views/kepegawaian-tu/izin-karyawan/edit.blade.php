@extends('layouts.kepegawaian-tu')

@section('content')
@php
    $lampiranMaxKb = max((int) config('kepegawaian_tu.izin.lampiran_max_kb', 2048), 1);
    $lampiranMaxMb = $lampiranMaxKb / 1024;
@endphp
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('kepegawaian-tu.izin-karyawan.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Pengajuan Izin</h1>
            <p class="mt-1 text-gray-600">Ubah pengajuan selama status masih diajukan.</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <form action="{{ route('kepegawaian-tu.izin-karyawan.update', $izinKaryawan) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
              x-data="{ jenis: @js(old('jenis', $izinKaryawan->jenis)) }">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="nama_karyawan" class="mb-2 block text-sm font-medium text-gray-700">Nama Karyawan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_karyawan" id="nama_karyawan" value="{{ old('nama_karyawan', $izinKaryawan->nama_karyawan) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('nama_karyawan') border-red-500 @enderror">
                    @error('nama_karyawan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="jenis" class="mb-2 block text-sm font-medium text-gray-700">Jenis <span class="text-red-500">*</span></label>
                    <select name="jenis" id="jenis" x-model="jenis" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('jenis') border-red-500 @enderror">
                        <option value="izin" {{ old('jenis', $izinKaryawan->jenis) === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="cuti" {{ old('jenis', $izinKaryawan->jenis) === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="sakit" {{ old('jenis', $izinKaryawan->jenis) === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="dinas_luar" {{ old('jenis', $izinKaryawan->jenis) === 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                    </select>
                    @error('jenis')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $izinKaryawan->tanggal_mulai->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $izinKaryawan->tanggal_selesai->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div x-show="jenis === 'dinas_luar'" x-cloak class="grid grid-cols-1 gap-6 rounded-lg border border-blue-100 bg-blue-50 p-4 sm:grid-cols-3">
                <div>
                    <label for="dinas_luar_hari" class="mb-2 block text-sm font-medium text-gray-700">Hari Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="dinas_luar_hari" id="dinas_luar_hari" value="{{ old('dinas_luar_hari', $izinKaryawan->dinas_luar_hari) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('dinas_luar_hari') border-red-500 @enderror">
                    @error('dinas_luar_hari')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="dinas_luar_waktu" class="mb-2 block text-sm font-medium text-gray-700">Waktu Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="dinas_luar_waktu" id="dinas_luar_waktu" value="{{ old('dinas_luar_waktu', $izinKaryawan->dinas_luar_waktu) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('dinas_luar_waktu') border-red-500 @enderror">
                    @error('dinas_luar_waktu')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="dinas_luar_tempat" class="mb-2 block text-sm font-medium text-gray-700">Tempat Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="dinas_luar_tempat" id="dinas_luar_tempat" value="{{ old('dinas_luar_tempat', $izinKaryawan->dinas_luar_tempat) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('dinas_luar_tempat') border-red-500 @enderror">
                    @error('dinas_luar_tempat')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="alasan" class="mb-2 block text-sm font-medium text-gray-700">Alasan <span class="text-red-500">*</span></label>
                <textarea name="alasan" id="alasan" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('alasan') border-red-500 @enderror">{{ old('alasan', $izinKaryawan->alasan) }}</textarea>
                @error('alasan')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="lampiran" class="mb-2 block text-sm font-medium text-gray-700">Lampiran Bukti</label>
                <input type="file" name="lampiran" id="lampiran" data-max-kb="{{ $lampiranMaxKb }}" data-file-label="Lampiran Izin" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('lampiran') border-red-500 @enderror">
                @if($izinKaryawan->lampiran)
                    <a href="{{ Storage::url($izinKaryawan->lampiran) }}" target="_blank" class="mt-1 inline-block text-xs text-blue-600 hover:underline">Lihat lampiran saat ini</a>
                @endif
                <p class="mt-1 text-xs text-gray-500">Format: jpg, jpeg, png, pdf. Maksimal {{ $lampiranMaxMb }}MB.</p>
                @error('lampiran')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <a href="{{ route('kepegawaian-tu.izin-karyawan.show', $izinKaryawan) }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
