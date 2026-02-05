@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.unit-komputer.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Unit Komputer</h1>
            <p class="text-gray-600 mt-1">Buat unit komputer baru di laboratorium</p>
        </div>
    </div>

    {{-- Form with Draft --}}
    <x-form-draft formId="create-unit-komputer" formName="Unit Komputer">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="create-unit-komputer" action="{{ route('laboran.unit-komputer.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Kode Unit --}}
                <div>
                    <label for="kode_unit" class="block text-sm font-medium text-gray-700 mb-2">Kode Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_unit" id="kode_unit" value="{{ old('kode_unit') }}" placeholder="Contoh: PC-001, PC-LAB1-01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('kode_unit') border-red-500 @enderror">
                    @error('kode_unit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Komputer 1, PC Meja 1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nama') border-red-500 @enderror">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Laboratorium --}}
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

                {{-- Nomor Meja --}}
                <div>
                    <label for="nomor_meja" class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja</label>
                    <input type="number" name="nomor_meja" id="nomor_meja" value="{{ old('nomor_meja') }}" min="1" max="100" placeholder="1-100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nomor_meja') border-red-500 @enderror">
                    @error('nomor_meja')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Kondisi --}}
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

                    {{-- Status --}}
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

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" placeholder="Catatan tambahan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('laboran.unit-komputer.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
    </x-form-draft>
</div>
@endsection
