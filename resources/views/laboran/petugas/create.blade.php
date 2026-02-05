@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.petugas.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Petugas Laboran</h1>
            <p class="text-gray-600 mt-1">Isi data petugas laboran baru</p>
        </div>
    </div>

    {{-- Form with Draft --}}
    <x-form-draft formId="create-petugas" formName="Petugas Laboran">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="create-petugas" action="{{ route('laboran.petugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- NIP --}}
            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP <span class="text-red-500">*</span></label>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nip') border-red-500 @enderror"
                       placeholder="Masukkan NIP">
                @error('nip')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap">
                @error('nama')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('email') border-red-500 @enderror"
                       placeholder="contoh@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- No Telepon --}}
            <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('no_telepon') border-red-500 @enderror"
                       placeholder="08xxxxxxxxxx">
                @error('no_telepon')
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
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto --}}
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('foto') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                @error('foto')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('laboran.petugas.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
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
