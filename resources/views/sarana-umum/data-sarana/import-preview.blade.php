@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.data-sarana.import') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Preview Import Sarana</h1>
            <p class="text-gray-600 mt-1">Menampilkan {{ $previewRows->count() }} dari total {{ $totalRows }} baris data.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Baris</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Lokasi</th>
                        <th class="px-4 py-3 text-center">Kondisi</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($previewRows as $row)
                        <tr>
                            <td class="px-4 py-3 text-gray-700">{{ $row['row'] }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $row['kode_inventaris'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row['nama'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row['jenis'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row['lokasi'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-center text-gray-700">{{ $row['kondisi'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-center text-gray-700">{{ $row['status'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('sarana-umum.data-sarana.import') }}"
           class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Ulangi Upload</a>
        <form action="{{ route('sarana-umum.data-sarana.import.process') }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">
                <x-heroicon-o-check class="w-4 h-4"/>
                Proses Import
            </button>
        </form>
    </div>
</div>
@endsection
