@extends('layouts.kepegawaian-tu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Arsip Digital TU</h1>
            <p class="mt-1 text-gray-600">Pusat dokumen final/arsip lintas workflow dengan versi, tag, dan retensi.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-slate-700">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-500">Total Dokumen</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-emerald-700">{{ $stats['berstatus_final'] }}</p>
            <p class="text-sm text-gray-500">Status Final</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-blue-700">{{ $stats['berstatus_arsip'] }}</p>
            <p class="text-sm text-gray-500">Status Arsip</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-red-700">{{ $stats['retensi_expired'] }}</p>
            <p class="text-sm text-gray-500">Retensi Lewat</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm">
        <form action="{{ route('kepegawaian-tu.arsip-digital.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau nomor dokumen..." class="rounded-lg border border-gray-300 px-4 py-2 text-sm min-w-[220px]">
            <select name="module" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Modul</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                @endforeach
            </select>
            <select name="status_sumber" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach($statusSumberOptions as $statusSumber)
                    <option value="{{ $statusSumber }}" {{ request('status_sumber') === $statusSumber ? 'selected' : '' }}>{{ ucfirst($statusSumber) }}</option>
                @endforeach
            </select>
            <select name="retensi" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Retensi</option>
                <option value="active" {{ request('retensi') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="expired" {{ request('retensi') === 'expired' ? 'selected' : '' }}>Lewat Retensi</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Filter</button>
            @if(request()->query())
                <a href="{{ route('kepegawaian-tu.arsip-digital.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul</th>
                        <th class="px-6 py-3 text-left">Modul</th>
                        <th class="px-6 py-3 text-left">Nomor</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Versi</th>
                        <th class="px-6 py-3 text-left">Retensi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($arsipDokumens as $arsip)
                        @php
                            $statusClass = match($arsip->status_sumber) {
                                'arsip' => 'bg-blue-100 text-blue-700',
                                'final' => 'bg-emerald-100 text-emerald-700',
                                'review' => 'bg-violet-100 text-violet-700',
                                'draft' => 'bg-gray-100 text-gray-700',
                                'selesai' => 'bg-green-100 text-green-700',
                                'diproses' => 'bg-amber-100 text-amber-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                            $retensiLewat = $arsip->retensi_sampai && $arsip->retensi_sampai->isPast();
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $arsip->judul }}</p>
                                <p class="text-xs text-gray-500">Update: {{ $arsip->updated_at?->format('d M Y H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $arsip->module }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $arsip->nomor_dokumen ?: '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $arsip->tanggal_dokumen?->format('d M Y') ?: '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                                    {{ $arsip->status_sumber ? ucfirst($arsip->status_sumber) : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">v{{ $arsip->version }}</td>
                            <td class="px-6 py-4">
                                @if($arsip->retensi_sampai)
                                    <span class="text-sm {{ $retensiLewat ? 'text-red-600 font-medium' : 'text-gray-700' }}">
                                        {{ $arsip->retensi_sampai->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kepegawaian-tu.arsip-digital.show', $arsip) }}" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                                    <x-heroicon-o-eye class="h-4 w-4"/>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">Belum ada dokumen dalam arsip digital.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($arsipDokumens->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $arsipDokumens->links() }}</div>
        @endif
    </div>
</div>
@endsection
