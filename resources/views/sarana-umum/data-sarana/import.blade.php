@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.data-sarana.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Import Data Sarana</h1>
            <p class="text-gray-600 mt-1">Upload file CSV untuk menambahkan atau memperbarui data sarana umum secara massal.</p>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
        <p class="text-sm text-amber-800">
            Pastikan kolom wajib ada: <span class="font-semibold">kode_inventaris, nama, jenis, lokasi, kondisi, status</span>.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('sarana-umum.data-sarana.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    File CSV <span class="text-red-500">*</span>
                </label>
                <input type="file"
                       name="file"
                       id="file"
                       accept=".csv,.txt"
                       data-max-kb="1024"
                       data-file-label="File CSV"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('file') border-red-500 @enderror">
                @error('file')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">Maksimal ukuran file 1MB.</p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <a href="{{ route('sarana-umum.data-sarana.template') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                    <x-heroicon-o-document-arrow-down class="w-4 h-4"/>
                    Download Template
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">
                    <x-heroicon-o-arrow-up-tray class="w-4 h-4"/>
                    Preview Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
