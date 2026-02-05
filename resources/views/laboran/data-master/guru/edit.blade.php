@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.data-master.guru.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Guru</h1>
            <p class="text-gray-600 mt-1">Ubah data guru: {{ $guru->nama }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('laboran.data-master.guru.update', $guru) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip', $guru->nip) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nip') border-red-500 @enderror"
                           placeholder="Masukkan NIP (opsional)">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $guru->nama) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $guru->email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $guru->no_telepon) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('no_telepon') border-red-500 @enderror"
                           placeholder="08xxxxxxxxxx">
                    @error('no_telepon')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="aktif" {{ old('status', $guru->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $guru->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('laboran.data-master.guru.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
