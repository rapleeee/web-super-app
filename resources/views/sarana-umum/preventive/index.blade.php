@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Preventive Maintenance</h1>
            <p class="mt-1 text-gray-600">Jadwal maintenance berkala untuk menjaga sarana umum tetap optimal.</p>
        </div>
        <a href="{{ route('sarana-umum.preventive-maintenance.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Jadwal
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-gray-900">{{ $summary['total'] }}</p>
            <p class="text-sm text-gray-500">Total Jadwal</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-green-600">{{ $summary['aktif'] }}</p>
            <p class="text-sm text-gray-500">Aktif</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-amber-600">{{ $summary['jatuh_tempo_7_hari'] }}</p>
            <p class="text-sm text-gray-500">Jatuh Tempo 7 Hari</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-2xl font-bold text-red-600">{{ $summary['overdue'] }}</p>
            <p class="text-sm text-gray-500">Overdue</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('sarana-umum.preventive-maintenance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm sm:w-auto">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
            </select>
            <button type="submit" class="rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">Filter</button>
            @if (request('status'))
                <a href="{{ route('sarana-umum.preventive-maintenance.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left">Sarana</th>
                    <th class="px-6 py-3 text-left">Tugas</th>
                    <th class="px-6 py-3 text-center">Interval</th>
                    <th class="px-6 py-3 text-center">Jadwal Berikutnya</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($preventives as $preventive)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $preventive->saranaUmum?->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $preventive->saranaUmum?->kode_inventaris ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $preventive->nama_tugas }}</td>
                        <td class="px-6 py-4 text-center text-gray-700">{{ $preventive->interval_hari }} hari</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-gray-700">{{ $preventive->tanggal_maintenance_berikutnya->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$preventive->is_active)
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Non Aktif</span>
                            @elseif($preventive->is_overdue)
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Overdue</span>
                            @else
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('sarana-umum.preventive-maintenance.complete', $preventive) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Tandai Selesai">
                                        <x-heroicon-o-check-circle class="h-5 w-5"/>
                                    </button>
                                </form>
                                <a href="{{ route('sarana-umum.preventive-maintenance.show', $preventive) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                    <x-heroicon-o-eye class="h-5 w-5"/>
                                </a>
                                <a href="{{ route('sarana-umum.preventive-maintenance.edit', $preventive) }}" class="text-amber-600 hover:text-amber-800" title="Edit">
                                    <x-heroicon-o-pencil-square class="h-5 w-5"/>
                                </a>
                                <form id="delete-preventive-{{ $preventive->id }}" action="{{ route('sarana-umum.preventive-maintenance.destroy', $preventive) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 hover:text-red-800" title="Hapus"
                                            onclick="confirmDelete('delete-preventive-{{ $preventive->id }}', 'Hapus Jadwal?', 'Jadwal preventive akan dihapus permanen!')">
                                        <x-heroicon-o-trash class="h-5 w-5"/>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada jadwal preventive maintenance.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($preventives->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $preventives->links() }}</div>
        @endif
    </div>
</div>
@endsection
