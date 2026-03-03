@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.preventive-maintenance.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Jadwal Preventive</h1>
            <p class="mt-1 text-gray-600">Buat jadwal perawatan berkala sarana umum.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('sarana-umum.preventive-maintenance.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="sarana_umum_id" class="mb-2 block text-sm font-medium text-gray-700">Sarana Umum <span class="text-red-500">*</span></label>
                <select name="sarana_umum_id" id="sarana_umum_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('sarana_umum_id') border-red-500 @enderror">
                    <option value="">Pilih Sarana</option>
                    @foreach($saranaUmums as $sarana)
                        <option value="{{ $sarana->id }}" {{ old('sarana_umum_id') == $sarana->id ? 'selected' : '' }}>{{ $sarana->nama }} ({{ $sarana->kode_inventaris }})</option>
                    @endforeach
                </select>
                @error('sarana_umum_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="nama_tugas" class="mb-2 block text-sm font-medium text-gray-700">Nama Tugas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tugas" id="nama_tugas" value="{{ old('nama_tugas') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('nama_tugas') border-red-500 @enderror">
                    @error('nama_tugas')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="interval_hari" class="mb-2 block text-sm font-medium text-gray-700">Interval (Hari) <span class="text-red-500">*</span></label>
                    <input type="number" min="1" name="interval_hari" id="interval_hari" value="{{ old('interval_hari', 30) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('interval_hari') border-red-500 @enderror">
                    @error('interval_hari')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <label for="toleransi_hari" class="mb-2 block text-sm font-medium text-gray-700">Toleransi (Hari)</label>
                    <input type="number" min="0" name="toleransi_hari" id="toleransi_hari" value="{{ old('toleransi_hari', 0) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('toleransi_hari') border-red-500 @enderror">
                    @error('toleransi_hari')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', now()->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal_maintenance_berikutnya" class="mb-2 block text-sm font-medium text-gray-700">Jadwal Berikutnya <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_maintenance_berikutnya" id="tanggal_maintenance_berikutnya" value="{{ old('tanggal_maintenance_berikutnya', now()->addDays(30)->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tanggal_maintenance_berikutnya') border-red-500 @enderror">
                    @error('tanggal_maintenance_berikutnya')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="deskripsi" class="mb-2 block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex items-center gap-3 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="rounded border-gray-300 text-[#272125] focus:ring-[#272125]">
                Jadwal aktif
            </label>

            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <a href="{{ route('sarana-umum.preventive-maintenance.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
