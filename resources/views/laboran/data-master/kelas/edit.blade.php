@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.data-master.kelas.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kelas</h1>
            <p class="text-gray-600 mt-1">Ubah data: {{ $kelas->nama_lengkap }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('laboran.data-master.kelas.update', $kelas) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" id="tingkat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('tingkat') border-red-500 @enderror">
                        <option value="">Pilih Tingkat</option>
                        @foreach (\App\Models\Kelas::tingkatOptions() as $tingkat)
                            <option value="{{ $tingkat }}" {{ old('tingkat', $kelas->tingkat) === $tingkat ? 'selected' : '' }}>Kelas {{ $tingkat }}</option>
                        @endforeach
                    </select>
                    @error('tingkat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                    <select name="jurusan" id="jurusan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('jurusan') border-red-500 @enderror">
                        <option value="">Pilih Jurusan</option>
                        @foreach (\App\Models\Kelas::jurusanOptions() as $jurusan)
                            <option value="{{ $jurusan }}" {{ old('jurusan', $kelas->jurusan) === $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                        @endforeach
                    </select>
                    @error('jurusan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rombel" class="block text-sm font-medium text-gray-700 mb-2">Rombel <span class="text-red-500">*</span></label>
                    <select name="rombel" id="rombel" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('rombel') border-red-500 @enderror">
                        <option value="">Pilih Rombel</option>
                        @foreach (\App\Models\Kelas::rombelOptions() as $rombel)
                            <option value="{{ $rombel }}" {{ old('rombel', $kelas->rombel) === $rombel ? 'selected' : '' }}>{{ $rombel }}</option>
                        @endforeach
                    </select>
                    @error('rombel')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="aktif" {{ old('status', $kelas->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $kelas->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('laboran.data-master.kelas.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
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
