@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('laboran.unit-komputer.import') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Preview Import</h1>
            <p class="text-gray-600 mt-1">Periksa data sebelum melakukan import</p>
        </div>
    </div>

    {{-- Steps --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center">
                    <x-heroicon-o-check class="w-6 h-6"/>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Upload File</p>
                    <p class="text-sm text-green-600">Selesai</p>
                </div>
            </div>
            <div class="flex-1 h-1 mx-4 bg-green-500 rounded"></div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#272125] text-white flex items-center justify-center font-bold">2</div>
                <div>
                    <p class="font-medium text-gray-900">Preview</p>
                    <p class="text-sm text-gray-500">Periksa data</p>
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
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <x-heroicon-o-document-text class="w-6 h-6 text-blue-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ count($previewData) }}</p>
                    <p class="text-sm text-gray-500">Total Baris</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $validCount }}</p>
                    <p class="text-sm text-gray-500">Data Valid</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $invalidCount }}</p>
                    <p class="text-sm text-gray-500">Data Error</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Warning if has errors --}}
    @if($invalidCount > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 flex-shrink-0"/>
                <div>
                    <h4 class="font-medium text-yellow-800">Perhatian!</h4>
                    <p class="text-sm text-yellow-700 mt-1">
                        Terdapat {{ $invalidCount }} baris dengan error. Baris dengan error akan <strong>dilewati</strong> saat import.
                        Hanya {{ $validCount }} baris valid yang akan diimport.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Preview Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Preview Data</h3>
            <div class="flex items-center gap-2 text-sm">
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded">
                    <x-heroicon-o-check-circle class="w-4 h-4"/> Valid
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded">
                    <x-heroicon-o-x-circle class="w-4 h-4"/> Error
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center w-16">Baris</th>
                        <th class="px-4 py-3 text-center w-16">Status</th>
                        <th class="px-4 py-3 text-left">Kode Unit</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Laboratorium</th>
                        <th class="px-4 py-3 text-center">Meja</th>
                        <th class="px-4 py-3 text-center">Kondisi</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-left">Error</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($previewData as $row)
                        <tr class="{{ $row['valid'] ? 'bg-white' : 'bg-red-50' }}">
                            <td class="px-4 py-3 text-center text-gray-500">{{ $row['row_number'] }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($row['valid'])
                                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mx-auto"/>
                                @else
                                    <x-heroicon-o-x-circle class="w-5 h-5 text-red-500 mx-auto"/>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $row['data']['kode_unit'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row['data']['nama'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row['data']['laboratorium'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $row['data']['nomor_meja'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if(!empty($row['data']['kondisi']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $row['data']['kondisi'] === 'baik' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $row['data']['kondisi'] === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $row['data']['kondisi'] === 'rusak_berat' ? 'bg-orange-100 text-orange-700' : '' }}
                                        {{ $row['data']['kondisi'] === 'mati_total' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ str_replace('_', ' ', $row['data']['kondisi']) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">baik</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(!empty($row['data']['status']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $row['data']['status'] === 'aktif' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $row['data']['status'] === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $row['data']['status'] === 'tidak_aktif' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ str_replace('_', ' ', $row['data']['status']) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(!empty($row['errors']))
                                    <ul class="text-xs text-red-600 list-disc list-inside">
                                        @foreach($row['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('laboran.unit-komputer.import') }}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <x-heroicon-o-arrow-left class="w-5 h-5"/>
                Kembali
            </a>

            @if($validCount > 0)
                <form action="{{ route('laboran.unit-komputer.import.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <x-heroicon-o-check class="w-5 h-5"/>
                        Import {{ $validCount }} Data Valid
                    </button>
                </form>
            @else
                <button type="button" disabled class="inline-flex items-center gap-2 px-6 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                    <x-heroicon-o-x-mark class="w-5 h-5"/>
                    Tidak Ada Data Valid
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
