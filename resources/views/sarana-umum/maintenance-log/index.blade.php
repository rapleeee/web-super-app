@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Maintenance Log</h1>
            <p class="text-gray-600 mt-1">Riwayat perbaikan dan perawatan sarana umum</p>
        </div>
        <a href="{{ route('sarana-umum.maintenance-log.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Lapor Masalah
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500">Pending</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['proses'] }}</p>
            <p class="text-sm text-gray-500">Diproses</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-green-600">{{ $stats['selesai'] }}</p>
            <p class="text-sm text-gray-500">Selesai</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-red-600">{{ $stats['tidak_bisa_diperbaiki'] }}</p>
            <p class="text-sm text-gray-500">Gagal Diperbaiki</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['sla_due_today'] }}</p>
            <p class="text-sm text-gray-500">SLA Jatuh Tempo Hari Ini</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-rose-600">{{ $stats['sla_breached'] }}</p>
            <p class="text-sm text-gray-500">SLA Terlewati</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($stats['total_biaya_bulan_ini'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500">Biaya Bulan Ini</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-lg font-bold text-emerald-700">Rp {{ number_format($stats['total_biaya'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500">Total Biaya</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('sarana-umum.maintenance-log.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="min-w-[220px]">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="tidak_bisa_diperbaiki" {{ request('status') === 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#272125] text-white rounded-lg hover:bg-[#3a3136] transition">Filter</button>
            @if(request('status'))
                <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Sarana</th>
                        <th class="px-6 py-4 text-left">Keluhan</th>
                        <th class="px-6 py-4 text-left">Pelapor</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">SLA</th>
                        <th class="px-6 py-4 text-right">Biaya</th>
                        <th class="px-6 py-4 text-center">Durasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($maintenanceLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-900">{{ $log->tanggal_lapor->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('sarana-umum.data-sarana.show', $log->saranaUmum) }}" class="font-medium text-blue-600 hover:underline">{{ $log->saranaUmum->nama }}</a>
                                <p class="text-xs text-gray-400">{{ $log->saranaUmum->kode_inventaris }} • {{ $log->saranaUmum->lokasi }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ Str::limit($log->keluhan, 50) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $log->pelapor?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $log->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $log->status === 'proses' ? 'bg-blue-100 text-blue-600' : '' }}
                                    {{ $log->status === 'selesai' ? 'bg-green-100 text-green-600' : '' }}
                                    {{ $log->status === 'tidak_bisa_diperbaiki' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($log->sla_deadline)
                                    @if($log->is_sla_breached)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Lewat ({{ $log->sla_deadline->format('d M') }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Aman ({{ $log->sla_deadline->format('d M') }})
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-gray-700">
                                {{ $log->biaya ? 'Rp '.number_format((float) $log->biaya, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $log->durasi ? $log->durasi.' hari' : '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('sarana-umum.maintenance-log.show', $log) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('sarana-umum.maintenance-log.edit', $log) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-log-{{ $log->id }}" action="{{ route('sarana-umum.maintenance-log.destroy', $log) }}" method="POST" class="inline">
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
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">Belum ada data maintenance log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($maintenanceLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $maintenanceLogs->links() }}</div>
        @endif
    </div>
</div>
@endsection
