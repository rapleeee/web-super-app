@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6"
     x-data="{
         unitId: '{{ $selectedUnit->id ?? old('unit_komputer_id', request('unit_id')) }}',
         komponens: @js($komponens->map(fn($k) => ['id' => $k->id, 'label' => $k->kategori->nama.' - '.$k->kode_inventaris, 'kondisi' => $k->kondisi])),
         selectedKomponen: '{{ old('komponen_perangkat_id', request('komponen_id')) }}',
         loading: false,

         async fetchKomponens() {
             if (!this.unitId) {
                 this.komponens = [];
                 this.selectedKomponen = '';
                 return;
             }
             this.loading = true;
             try {
                 const response = await fetch(`{{ url('laboran/maintenance-log/komponens') }}/${this.unitId}`);
                 this.komponens = await response.json();
                 this.selectedKomponen = '';
             } catch (error) {
                 console.error('Failed to fetch komponens:', error);
                 this.komponens = [];
             }
             this.loading = false;
         },

         getKondisiFromKomponen() {
             const komponen = this.komponens.find(k => k.id == this.selectedKomponen);
             return komponen ? komponen.kondisi : '';
         }
     }">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.maintenance-log.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lapor Masalah</h1>
            <p class="text-gray-600 mt-1">Buat laporan perbaikan baru</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('laboran.maintenance-log.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Unit Komputer --}}
                <div>
                    <label for="unit_komputer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Komputer <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_komputer_id" id="unit_komputer_id"
                            x-model="unitId"
                            @change="fetchKomponens()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                        <option value="">Pilih Unit Komputer</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">
                                {{ $unit->nama }} - {{ $unit->kode_unit }} ({{ $unit->laboratorium->nama }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Pilih unit komputer terlebih dahulu untuk melihat komponen</p>
                </div>

                {{-- Komponen --}}
                <div>
                    <label for="komponen_perangkat_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Komponen Perangkat <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="komponen_perangkat_id" id="komponen_perangkat_id"
                                x-model="selectedKomponen"
                                @change="$refs.kondisiSebelum.value = getKondisiFromKomponen()"
                                :disabled="!unitId || loading"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed @error('komponen_perangkat_id') border-red-500 @enderror">
                            <option value="">
                                <span x-show="!unitId">-- Pilih Unit Komputer Dulu --</span>
                                <span x-show="unitId && loading">Memuat...</span>
                                <span x-show="unitId && !loading && komponens.length === 0">-- Tidak Ada Komponen --</span>
                                <span x-show="unitId && !loading && komponens.length > 0">Pilih Komponen</span>
                            </option>
                            <template x-for="komponen in komponens" :key="komponen.id">
                                <option :value="komponen.id" x-text="komponen.label"></option>
                            </template>
                        </select>
                        <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    @error('komponen_perangkat_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Lapor --}}
                <div>
                    <label for="tanggal_lapor" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lapor <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lapor" id="tanggal_lapor" value="{{ old('tanggal_lapor', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tanggal_lapor') border-red-500 @enderror">
                    @error('tanggal_lapor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keluhan --}}
                <div>
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">Keluhan / Masalah <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" id="keluhan" rows="4" placeholder="Jelaskan masalah yang dialami..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keluhan') border-red-500 @enderror">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kondisi Sebelum --}}
                <div>
                    <label for="kondisi_sebelum" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Saat Ini <span class="text-red-500">*</span></label>
                    <select name="kondisi_sebelum" id="kondisi_sebelum" x-ref="kondisiSebelum"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kondisi_sebelum') border-red-500 @enderror">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik" {{ old('kondisi_sebelum') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi_sebelum') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi_sebelum') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="mati_total" {{ old('kondisi_sebelum') === 'mati_total' ? 'selected' : '' }}>Mati Total</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Otomatis terisi dari kondisi komponen saat ini</p>
                    @error('kondisi_sebelum')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ old('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan tambahan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('laboran.maintenance-log.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
