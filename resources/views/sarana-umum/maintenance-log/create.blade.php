@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lapor Masalah Sarana</h1>
            <p class="text-gray-600 mt-1">Buat laporan perbaikan sarana umum</p>
        </div>
    </div>

    <x-form-draft formId="create-maintenance-sarana" formName="Maintenance Sarana Umum">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="create-maintenance-sarana" action="{{ route('sarana-umum.maintenance-log.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="sarana_umum_id" class="block text-sm font-medium text-gray-700 mb-2">Sarana Umum <span class="text-red-500">*</span></label>
                <select name="sarana_umum_id" id="sarana_umum_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('sarana_umum_id') border-red-500 @enderror">
                    <option value="">Pilih Sarana</option>
                    @foreach ($saranaUmums as $sarana)
                        <option value="{{ $sarana->id }}" {{ old('sarana_umum_id') == $sarana->id ? 'selected' : '' }}>
                            {{ $sarana->nama }} ({{ $sarana->kode_inventaris }}) - {{ $sarana->lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('sarana_umum_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_lapor" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lapor <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_lapor" id="tanggal_lapor" value="{{ old('tanggal_lapor', date('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_lapor') border-red-500 @enderror">
                @error('tanggal_lapor')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sla_deadline" class="block text-sm font-medium text-gray-700 mb-2">SLA Deadline</label>
                <input type="date" name="sla_deadline" id="sla_deadline" value="{{ old('sla_deadline') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('sla_deadline') border-red-500 @enderror">
                @error('sla_deadline')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">Keluhan <span class="text-red-500">*</span></label>
                <textarea name="keluhan" id="keluhan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keluhan') border-red-500 @enderror">{{ old('keluhan') }}</textarea>
                @error('keluhan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="kondisi_sebelum" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Sebelum <span class="text-red-500">*</span></label>
                    <select name="kondisi_sebelum" id="kondisi_sebelum" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi_sebelum') border-red-500 @enderror">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik" {{ old('kondisi_sebelum') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi_sebelum') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi_sebelum') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="mati_total" {{ old('kondisi_sebelum') === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                    </select>
                    @error('kondisi_sebelum')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ old('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" id="catatan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="bukti_sebelum" class="block text-sm font-medium text-gray-700 mb-2">Bukti Sebelum</label>
                    <input type="file" name="bukti_sebelum" id="bukti_sebelum" data-max-kb="2048" data-file-label="Bukti Sebelum"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('bukti_sebelum') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Maks 2MB (jpg, jpeg, png, pdf)</p>
                    @error('bukti_sebelum')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="bukti_sesudah" class="block text-sm font-medium text-gray-700 mb-2">Bukti Sesudah</label>
                    <input type="file" name="bukti_sesudah" id="bukti_sesudah" data-max-kb="2048" data-file-label="Bukti Sesudah"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('bukti_sesudah') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Maks 2MB (jpg, jpeg, png, pdf)</p>
                    @error('bukti_sesudah')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="bukti_invoice" class="block text-sm font-medium text-gray-700 mb-2">Bukti Invoice</label>
                    <input type="file" name="bukti_invoice" id="bukti_invoice" data-max-kb="2048" data-file-label="Bukti Invoice"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('bukti_invoice') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Maks 2MB (jpg, jpeg, png, pdf)</p>
                    @error('bukti_invoice')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">Simpan</button>
            </div>
        </form>
    </div>
    </x-form-draft>
</div>
@endsection
