@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.maintenance-log.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Update Maintenance</h1>
            <p class="text-gray-600 mt-1">{{ $maintenanceLog->komponenPerangkat->kategori->nama }} - {{ $maintenanceLog->komponenPerangkat->kode_inventaris }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('laboran.maintenance-log.update', $maintenanceLog) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Komponen (readonly info) --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Komponen</p>
                    <p class="font-medium text-gray-900">{{ $maintenanceLog->komponenPerangkat->kategori->nama }}</p>
                    <p class="text-sm text-gray-600">{{ $maintenanceLog->komponenPerangkat->kode_inventaris }} • {{ $maintenanceLog->komponenPerangkat->unitKomputer->nama }}</p>
                </div>

                <input type="hidden" name="komponen_perangkat_id" value="{{ $maintenanceLog->komponen_perangkat_id }}">

                <div class="grid grid-cols-2 gap-4">
                    {{-- Tanggal Lapor --}}
                    <div>
                        <label for="tanggal_lapor" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lapor <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lapor" id="tanggal_lapor" value="{{ old('tanggal_lapor', $maintenanceLog->tanggal_lapor->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_lapor') border-red-500 @enderror">
                        @error('tanggal_lapor')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Perbaikan</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $maintenanceLog->tanggal_mulai?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Keluhan --}}
                <div>
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">Keluhan / Masalah <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" id="keluhan" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keluhan') border-red-500 @enderror">{{ old('keluhan', $maintenanceLog->keluhan) }}</textarea>
                    @error('keluhan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Diagnosa --}}
                <div>
                    <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">Diagnosa</label>
                    <textarea name="diagnosa" id="diagnosa" rows="2" placeholder="Hasil diagnosa penyebab masalah..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('diagnosa') border-red-500 @enderror">{{ old('diagnosa', $maintenanceLog->diagnosa) }}</textarea>
                    @error('diagnosa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tindakan --}}
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                    <textarea name="tindakan" id="tindakan" rows="2" placeholder="Langkah perbaikan yang dilakukan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tindakan') border-red-500 @enderror">{{ old('tindakan', $maintenanceLog->tindakan) }}</textarea>
                    @error('tindakan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Teknisi --}}
                    <div>
                        <label for="teknisi" class="block text-sm font-medium text-gray-700 mb-2">Teknisi</label>
                        <input type="text" name="teknisi" id="teknisi" value="{{ old('teknisi', $maintenanceLog->teknisi) }}" placeholder="Nama teknisi"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('teknisi') border-red-500 @enderror">
                        @error('teknisi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Biaya --}}
                    <div>
                        <label for="biaya" class="block text-sm font-medium text-gray-700 mb-2">Biaya (Rp)</label>
                        <input type="number" name="biaya" id="biaya" value="{{ old('biaya', $maintenanceLog->biaya) }}" min="0" step="1000" placeholder="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('biaya') border-red-500 @enderror">
                        @error('biaya')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Kondisi Sebelum --}}
                    <div>
                        <label for="kondisi_sebelum" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Sebelum</label>
                        <select name="kondisi_sebelum" id="kondisi_sebelum"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi_sebelum') border-red-500 @enderror">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="mati_total" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                        </select>
                        @error('kondisi_sebelum')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kondisi Sesudah --}}
                    <div>
                        <label for="kondisi_sesudah" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Sesudah</label>
                        <select name="kondisi_sesudah" id="kondisi_sesudah"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi_sesudah') border-red-500 @enderror">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik" {{ old('kondisi_sesudah', $maintenanceLog->kondisi_sesudah) === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi_sesudah', $maintenanceLog->kondisi_sesudah) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi_sesudah', $maintenanceLog->kondisi_sesudah) === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="mati_total" {{ old('kondisi_sesudah', $maintenanceLog->kondisi_sesudah) === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                        </select>
                        @error('kondisi_sesudah')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $maintenanceLog->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ old('status', $maintenanceLog->status) === 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ old('status', $maintenanceLog->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="tidak_bisa_diperbaiki" {{ old('status', $maintenanceLog->status) === 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $maintenanceLog->tanggal_selesai?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_selesai') border-red-500 @enderror">
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" id="catatan" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan', $maintenanceLog->catatan) }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Box --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-2">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"/>
                        <p class="text-sm text-yellow-700">
                            Jika status diubah ke "Selesai", kondisi komponen akan otomatis diupdate sesuai kondisi sesudah.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('laboran.maintenance-log.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
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
