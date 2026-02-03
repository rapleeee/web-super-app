@extends('layouts.laboran')

@section('content')
<div class="max-w-full mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.unit-komputer.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Import Unit Komputer</h1>
            <p class="text-gray-600 mt-1">Upload file CSV atau Excel untuk import data</p>
        </div>
    </div>

    {{-- Steps --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#272125] text-white flex items-center justify-center font-bold">1</div>
                <div>
                    <p class="font-medium text-gray-900">Upload File</p>
                    <p class="text-sm text-gray-500">Pilih file CSV/Excel</p>
                </div>
            </div>
            <div class="flex-1 h-1 mx-4 bg-gray-200 rounded"></div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">2</div>
                <div>
                    <p class="font-medium text-gray-400">Preview</p>
                    <p class="text-sm text-gray-400">Periksa data</p>
                </div>
            </div>
            <div class="flex-1 h-1 mx-4 bg-gray-200 rounded"></div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">3</div>
                <div>
                    <p class="font-medium text-gray-400">Konfirmasi</p>
                    <p class="text-sm text-gray-400">Import data</p>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="w-6 h-6 text-blue-600 flex-shrink-0"/>
                <div>
                    <h4 class="font-medium text-blue-800 mb-2">Petunjuk Import:</h4>
                    <ol class="text-sm text-blue-700 list-decimal list-inside space-y-1">
                        <li>Download template CSV terlebih dahulu</li>
                        <li>Isi data sesuai format kolom yang tersedia</li>
                        <li>Pastikan nama <strong>laboratorium</strong> sesuai dengan data yang sudah ada di sistem</li>
                        <li>Upload file CSV/Excel yang sudah diisi</li>
                        <li>Preview dan periksa data sebelum import</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Format Info --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h4 class="font-medium text-gray-800 mb-3">Format Kolom:</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-700">Kolom</th>
                            <th class="px-3 py-2 text-center font-medium text-gray-700">Wajib</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-700">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">kode_unit</td>
                            <td class="px-3 py-2 text-center"><span class="text-green-600">✓</span></td>
                            <td class="px-3 py-2 text-gray-600">Kode unik unit (contoh: PC-001)</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">nama</td>
                            <td class="px-3 py-2 text-center"><span class="text-green-600">✓</span></td>
                            <td class="px-3 py-2 text-gray-600">Nama unit komputer</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">laboratorium</td>
                            <td class="px-3 py-2 text-center"><span class="text-green-600">✓</span></td>
                            <td class="px-3 py-2 text-gray-600">Nama laboratorium (harus sudah ada di sistem)</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">nomor_meja</td>
                            <td class="px-3 py-2 text-center"><span class="text-gray-400">-</span></td>
                            <td class="px-3 py-2 text-gray-600">Nomor meja/posisi (angka)</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">kondisi</td>
                            <td class="px-3 py-2 text-center"><span class="text-gray-400">-</span></td>
                            <td class="px-3 py-2 text-gray-600">baik / rusak_ringan / rusak_berat / mati_total</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">status</td>
                            <td class="px-3 py-2 text-center"><span class="text-gray-400">-</span></td>
                            <td class="px-3 py-2 text-gray-600">aktif / dalam_perbaikan / tidak_aktif</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-900">keterangan</td>
                            <td class="px-3 py-2 text-center"><span class="text-gray-400">-</span></td>
                            <td class="px-3 py-2 text-gray-600">Catatan tambahan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Download Template --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('laboran.unit-komputer.template') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-700 hover:border-[#272125] hover:text-[#272125] transition">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5"/>
                Download Template CSV
            </a>
            <span class="text-sm text-gray-500">Template berisi contoh data yang valid</span>
        </div>

        {{-- Upload Form --}}
        <form action="{{ route('laboran.unit-komputer.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih File <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#272125] transition"
                     x-data="{ fileName: '' }"
                     @dragover.prevent="$el.classList.add('border-[#272125]', 'bg-gray-50')"
                     @dragleave.prevent="$el.classList.remove('border-[#272125]', 'bg-gray-50')"
                     @drop.prevent="$el.classList.remove('border-[#272125]', 'bg-gray-50'); fileName = $event.dataTransfer.files[0]?.name || ''; $refs.fileInput.files = $event.dataTransfer.files">
                    <div class="space-y-2 text-center">
                        <x-heroicon-o-document-arrow-up class="mx-auto h-12 w-12 text-gray-400"/>
                        <div class="flex text-sm text-gray-600">
                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-[#272125] hover:text-[#3a3136] focus-within:outline-none">
                                <span x-show="!fileName">Pilih file</span>
                                <span x-show="fileName" x-text="fileName" class="text-green-600"></span>
                                <input id="file" name="file" type="file" class="sr-only" accept=".csv,.xlsx,.xls" required
                                       x-ref="fileInput"
                                       @change="fileName = $event.target.files[0]?.name || ''">
                            </label>
                            <p class="pl-1" x-show="!fileName">atau drag & drop</p>
                        </div>
                        <p class="text-xs text-gray-500">CSV, XLSX, XLS maksimal 2MB</p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('laboran.unit-komputer.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                    Lanjut Preview
                    <x-heroicon-o-arrow-right class="w-5 h-5"/>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
