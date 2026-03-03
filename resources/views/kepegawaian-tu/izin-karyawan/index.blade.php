@extends('layouts.kepegawaian-tu')

@section('content')
@php
    $isPrivilegedUser = in_array(auth()->user()?->role, ['admin', 'pejabat'], true);
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Izin Karyawan</h1>
            <p class="mt-1 text-gray-600">Kelola pengajuan izin, cuti, sakit, dan dinas luar dengan approval.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($isPrivilegedUser)
                <a href="{{ route('kepegawaian-tu.pusat-approval.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50 transition">
                    <x-heroicon-o-clipboard-document-check class="w-4 h-4"/>
                    Pusat Approval
                </a>
            @endif
            <a href="{{ route('kepegawaian-tu.izin-karyawan.export', request()->query()) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4"/>
                Export CSV
            </a>
            <a href="{{ route('kepegawaian-tu.izin-karyawan.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
                <x-heroicon-o-plus class="w-4 h-4"/>
                Ajukan Izin
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-amber-600">{{ $stats['diajukan'] }}</p>
            <p class="text-sm text-gray-500">Diajukan</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</p>
            <p class="text-sm text-gray-500">Disetujui</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</p>
            <p class="text-sm text-gray-500">Ditolak</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['overdue_sla'] }}</p>
            <p class="text-sm text-gray-500">Lewat SLA (>{{ $slaHari }} hari)</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm">
        <form action="{{ route('kepegawaian-tu.izin-karyawan.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/alasan..."
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm min-w-[220px]">
            <select name="status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="jenis" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Jenis</option>
                <option value="izin" {{ request('jenis') === 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="cuti" {{ request('jenis') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="dinas_luar" {{ request('jenis') === 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
            </select>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
            <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Filter</button>
            @if(request()->filled('status') || request()->filled('jenis') || request()->filled('search') || request()->filled('tanggal_mulai') || request()->filled('tanggal_selesai'))
                <a href="{{ route('kepegawaian-tu.izin-karyawan.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Karyawan</th>
                        <th class="px-6 py-3 text-left">Nomor</th>
                        <th class="px-6 py-3 text-left">Jenis</th>
                        <th class="px-6 py-3 text-left">Periode</th>
                        <th class="px-6 py-3 text-center">Durasi</th>
                        <th class="px-6 py-3 text-center">Menunggu</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($izins as $izin)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $izin->nama_karyawan }}</p>
                                <p class="text-xs text-gray-500">Pengaju: {{ $izin->pemohon?->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $izin->nomor_pengajuan }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ strtoupper(str_replace('_', ' ', $izin->jenis)) }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $izin->tanggal_mulai->format('d M Y') }} - {{ $izin->tanggal_selesai->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center text-gray-700">{{ $izin->durasi_hari }} hari</td>
                            <td class="px-6 py-4 text-center {{ $izin->status === 'diajukan' && $izin->hari_menunggu > $slaHari ? 'text-red-600 font-medium' : 'text-gray-700' }}">
                                {{ $izin->status === 'diajukan' ? $izin->hari_menunggu.' hari' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $izin->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $izin->status === 'disetujui' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $izin->status === 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($izin->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kepegawaian-tu.izin-karyawan.show', $izin) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                    <x-heroicon-o-eye class="w-5 h-5 inline"/>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">Belum ada pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($izins->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $izins->links() }}</div>
        @endif
    </div>
</div>
@endsection
