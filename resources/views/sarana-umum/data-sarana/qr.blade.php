@extends('layouts.sarpras-umum')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sarana-umum.data-sarana.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">QR Code Sarana</h1>
            <p class="text-gray-600 mt-1">{{ $saranaUmum->nama }} ({{ $saranaUmum->kode_inventaris }})</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8">
        <div class="flex flex-col items-center gap-6">
            <img src="{{ $qrUrl }}" alt="QR {{ $saranaUmum->nama }}" class="w-64 h-64 rounded-xl border border-gray-200 p-3">
            <div class="text-center">
                <p class="text-sm text-gray-500">QR mengarah ke:</p>
                <a href="{{ $targetUrl }}" class="text-sm text-blue-600 hover:underline break-all">{{ $targetUrl }}</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ $qrUrl }}"
                   download="qr-{{ $saranaUmum->kode_inventaris }}.png"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4"/>
                    Download QR
                </a>
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">
                    <x-heroicon-o-printer class="w-4 h-4"/>
                    Cetak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
