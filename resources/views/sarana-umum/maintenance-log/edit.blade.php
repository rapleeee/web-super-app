@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Update Maintenance</h1>
            <p class="text-gray-600 mt-1">{{ $maintenanceLog->saranaUmum->nama }} - {{ $maintenanceLog->saranaUmum->kode_inventaris }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('sarana-umum.maintenance-log.update', $maintenanceLog) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="sarana_umum_id" class="block text-sm font-medium text-gray-700 mb-2">Sarana Umum <span class="text-red-500">*</span></label>
                <select name="sarana_umum_id" id="sarana_umum_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('sarana_umum_id') border-red-500 @enderror">
                    @foreach ($saranaUmums as $sarana)
                        <option value="{{ $sarana->id }}" {{ old('sarana_umum_id', $maintenanceLog->sarana_umum_id) == $sarana->id ? 'selected' : '' }}>
                            {{ $sarana->nama }} ({{ $sarana->kode_inventaris }}) - {{ $sarana->lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('sarana_umum_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_lapor" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lapor <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lapor" id="tanggal_lapor" value="{{ old('tanggal_lapor', $maintenanceLog->tanggal_lapor->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_lapor') border-red-500 @enderror">
                    @error('tanggal_lapor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="sla_deadline" class="block text-sm font-medium text-gray-700 mb-2">SLA Deadline</label>
                    <input type="date" name="sla_deadline" id="sla_deadline" value="{{ old('sla_deadline', $maintenanceLog->sla_deadline?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('sla_deadline') border-red-500 @enderror">
                    @error('sla_deadline')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $maintenanceLog->tanggal_mulai?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $maintenanceLog->tanggal_selesai?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">Keluhan <span class="text-red-500">*</span></label>
                <textarea name="keluhan" id="keluhan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keluhan') border-red-500 @enderror">{{ old('keluhan', $maintenanceLog->keluhan) }}</textarea>
                @error('keluhan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">Diagnosa</label>
                <textarea name="diagnosa" id="diagnosa" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('diagnosa') border-red-500 @enderror">{{ old('diagnosa', $maintenanceLog->diagnosa) }}</textarea>
                @error('diagnosa')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                <textarea name="tindakan" id="tindakan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tindakan') border-red-500 @enderror">{{ old('tindakan', $maintenanceLog->tindakan) }}</textarea>
                @error('tindakan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="teknisi" class="block text-sm font-medium text-gray-700 mb-2">Teknisi</label>
                    <input type="text" name="teknisi" id="teknisi" value="{{ old('teknisi', $maintenanceLog->teknisi) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('teknisi') border-red-500 @enderror">
                    @error('teknisi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="biaya" class="block text-sm font-medium text-gray-700 mb-2">Biaya (Rp)</label>
                    <input type="number" name="biaya" id="biaya" value="{{ old('biaya', $maintenanceLog->biaya) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('biaya') border-red-500 @enderror">
                    @error('biaya')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="kondisi_sebelum" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Sebelum <span class="text-red-500">*</span></label>
                    <select name="kondisi_sebelum" id="kondisi_sebelum" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi_sebelum') border-red-500 @enderror">
                        <option value="baik" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="mati_total" {{ old('kondisi_sebelum', $maintenanceLog->kondisi_sebelum) === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                    </select>
                    @error('kondisi_sebelum')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="kondisi_sesudah" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Sesudah</label>
                    <select name="kondisi_sesudah" id="kondisi_sesudah" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi_sesudah') border-red-500 @enderror">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', $maintenanceLog->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ old('status', $maintenanceLog->status) === 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status', $maintenanceLog->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="tidak_bisa_diperbaiki" {{ old('status', $maintenanceLog->status) === 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" id="catatan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan', $maintenanceLog->catatan) }}</textarea>
                @error('catatan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="bukti_sebelum" class="block text-sm font-medium text-gray-700 mb-2">Bukti Sebelum</label>
                    <input type="file" name="bukti_sebelum" id="bukti_sebelum" data-max-kb="2048" data-file-label="Bukti Sebelum"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('bukti_sebelum') border-red-500 @enderror">
                    @if($maintenanceLog->bukti_sebelum)
                        <a href="{{ Storage::url($maintenanceLog->bukti_sebelum) }}" target="_blank" class="mt-1 inline-block text-xs text-blue-600 hover:underline">Lihat lampiran saat ini</a>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">Maks 2MB (jpg, jpeg, png, pdf)</p>
                    @error('bukti_sebelum')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="bukti_sesudah" class="block text-sm font-medium text-gray-700 mb-2">Bukti Sesudah</label>
                    <input type="file" name="bukti_sesudah" id="bukti_sesudah" data-max-kb="2048" data-file-label="Bukti Sesudah"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('bukti_sesudah') border-red-500 @enderror">
                    @if($maintenanceLog->bukti_sesudah)
                        <a href="{{ Storage::url($maintenanceLog->bukti_sesudah) }}" target="_blank" class="mt-1 inline-block text-xs text-blue-600 hover:underline">Lihat lampiran saat ini</a>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">Maks 2MB (jpg, jpeg, png, pdf)</p>
                    @error('bukti_sesudah')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="bukti_invoice" class="block text-sm font-medium text-gray-700 mb-2">Bukti Invoice</label>
                    <input type="file" name="bukti_invoice" id="bukti_invoice" data-max-kb="2048" data-file-label="Bukti Invoice"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('bukti_invoice') border-red-500 @enderror">
                    @if($maintenanceLog->bukti_invoice)
                        <a href="{{ Storage::url($maintenanceLog->bukti_invoice) }}" target="_blank" class="mt-1 inline-block text-xs text-blue-600 hover:underline">Lihat lampiran saat ini</a>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">Maks 2MB (jpg, jpeg, png, pdf)</p>
                    @error('bukti_invoice')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
