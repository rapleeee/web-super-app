@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Maintenance Log</h1>
            <p class="text-gray-600 mt-1">Riwayat perbaikan dan perawatan perangkat</p>
        </div>
        <a href="{{ route('laboran.maintenance-log.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Lapor Masalah
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <x-heroicon-o-clock class="w-5 h-5 text-gray-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Pending</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <x-heroicon-o-cog-6-tooth class="w-5 h-5 text-blue-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['proses'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Diproses</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['selesai'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Selesai</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <x-heroicon-o-x-circle class="w-5 h-5 text-red-600"/>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tidak_bisa_diperbaiki'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Gagal Diperbaiki</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('laboran.maintenance-log.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[150px]">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="tidak_bisa_diperbaiki" {{ request('status') === 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">
                Filter
            </button>
            @if(request('status'))
                <a href="{{ route('laboran.maintenance-log.index') }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Komponen</th>
                        <th class="px-6 py-4 text-left">Keluhan</th>
                        <th class="px-6 py-4 text-left">Pelapor</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Durasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($maintenanceLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-900">
                                {{ $log->tanggal_lapor->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-cube class="w-4 h-4 text-gray-400"/>
                                    <div>
                                        <a href="{{ route('laboran.komponen-perangkat.show', $log->komponenPerangkat) }}" class="text-blue-600 hover:underline font-medium">
                                            {{ $log->komponenPerangkat->kategori->nama }}
                                        </a>
                                        <p class="text-xs text-gray-400">{{ $log->komponenPerangkat->kode_inventaris }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ Str::limit($log->keluhan, 50) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $log->pelapor->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $log->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $log->status === 'proses' ? 'bg-blue-100 text-blue-600' : '' }}
                                    {{ $log->status === 'selesai' ? 'bg-green-100 text-green-600' : '' }}
                                    {{ $log->status === 'tidak_bisa_diperbaiki' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">
                                @if($log->durasi)
                                    {{ $log->durasi }} hari
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.maintenance-log.show', $log) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.maintenance-log.edit', $log) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-log-{{ $log->id }}" action="{{ route('laboran.maintenance-log.destroy', $log) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-log-{{ $log->id }}', 'Hapus Log?', 'Data maintenance akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-wrench-screwdriver class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data maintenance log.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($maintenanceLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $maintenanceLogs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
