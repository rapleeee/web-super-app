@extends('layouts.kepegawaian-tu')

@section('content')
@php
    $isPrivilegedUser = in_array(auth()->user()?->role, ['admin', 'pejabat'], true);
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inbox Berita Acara Final</h1>
            <p class="mt-1 text-gray-600">Dokumen final lintas modul untuk tindak lanjut administratif TU.</p>
        </div>
        <a href="{{ route('kepegawaian-tu.berita-acara-final.export', request()->query()) }}"
           class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
            <x-heroicon-o-arrow-down-tray class="w-4 h-4"/>
            Export CSV
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-7">
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-slate-700">{{ $summary['total'] }}</p>
            <p class="text-sm text-gray-500">Total</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-blue-700">{{ $summary['laboran'] }}</p>
            <p class="text-sm text-gray-500">Laboran</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-emerald-700">{{ $summary['sarana_umum'] }}</p>
            <p class="text-sm text-gray-500">Sarana Umum</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-amber-700">{{ $summary['baru'] }}</p>
            <p class="text-sm text-gray-500">Baru</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-violet-700">{{ $summary['diproses'] }}</p>
            <p class="text-sm text-gray-500">Diproses</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-green-700">{{ $summary['selesai'] }}</p>
            <p class="text-sm text-gray-500">Selesai</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-cyan-700">{{ $summary['arsip'] }}</p>
            <p class="text-sm text-gray-500">Arsip</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm">
        <form action="{{ route('kepegawaian-tu.berita-acara-final.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
            <select name="sumber" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Sumber</option>
                <option value="laboran" {{ request('sumber') === 'laboran' ? 'selected' : '' }}>Laboran</option>
                <option value="sarana_umum" {{ request('sumber') === 'sarana_umum' ? 'selected' : '' }}>Sarana Umum</option>
            </select>
            <select name="tindak_lanjut_status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Tindak Lanjut</option>
                @foreach($statusOptions as $statusKey => $statusLabel)
                    <option value="{{ $statusKey }}" {{ request('tindak_lanjut_status') === $statusKey ? 'selected' : '' }}>{{ $statusLabel }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Filter</button>
            @if(request()->query())
                <a href="{{ route('kepegawaian-tu.berita-acara-final.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Sumber</th>
                        <th class="px-6 py-3 text-left">Nama Guru</th>
                        <th class="px-6 py-3 text-left">Ruangan/Lab</th>
                        <th class="px-6 py-3 text-left">Kegiatan</th>
                        <th class="px-6 py-3 text-left">Petugas</th>
                        <th class="px-6 py-3 text-center">Tindak Lanjut</th>
                        <th class="px-6 py-3 text-left">Tag</th>
                        <th class="px-6 py-3 text-left">Catatan</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                        @php
                            $badgeClass = match($record['tindak_lanjut_status']) {
                                'diproses' => 'bg-violet-100 text-violet-700',
                                'selesai' => 'bg-green-100 text-green-700',
                                'arsip' => 'bg-cyan-100 text-cyan-700',
                                default => 'bg-amber-100 text-amber-700',
                            };
                            $updateAction = route('kepegawaian-tu.berita-acara-final.tindak-lanjut', ['sourceType' => $record['source_type'], 'sourceId' => $record['source_id']]);
                            $queryString = request()->getQueryString();
                            $updateUrl = $queryString ? $updateAction.'?'.$queryString : $updateAction;
                        @endphp
                        <tr class="align-top">
                            <td class="px-6 py-4 text-gray-700">{{ $record['tanggal']->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $record['sumber'] === 'Laboran' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $record['sumber'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-900">{{ $record['nama_guru'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $record['ruangan'] }}</td>
                            <td class="px-6 py-4 text-gray-700 max-w-xs break-words">{{ $record['kegiatan'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $record['petugas'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                    {{ $statusOptions[$record['tindak_lanjut_status']] ?? ucfirst($record['tindak_lanjut_status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                @if(empty($record['tags']))
                                    <span class="text-xs text-gray-400">-</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($record['tags'] as $tag)
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700 max-w-xs break-words">{{ $record['tindak_lanjut_catatan'] ?: '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ $record['route_detail'] }}" class="text-blue-600 hover:text-blue-800" title="Lihat Detail Sumber">
                                        <x-heroicon-o-eye class="w-5 h-5 inline"/>
                                    </a>
                                </div>
                                @if($isPrivilegedUser)
                                    <form action="{{ $updateUrl }}" method="POST" class="mt-3 space-y-2 text-left min-w-[220px]">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-xs">
                                            @foreach($statusOptions as $statusKey => $statusLabel)
                                                <option value="{{ $statusKey }}" {{ $record['tindak_lanjut_status'] === $statusKey ? 'selected' : '' }}>{{ $statusLabel }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="tags" value="{{ implode(', ', $record['tags'] ?? []) }}" placeholder="Tag: final, urgent" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-xs">
                                        <textarea name="catatan" rows="2" placeholder="Catatan tindak lanjut" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-xs">{{ $record['tindak_lanjut_catatan'] }}</textarea>
                                        <button type="submit" class="w-full rounded-lg bg-slate-700 px-3 py-2 text-xs font-medium text-white hover:bg-slate-800 transition">Simpan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">Belum ada berita acara final yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $records->links() }}</div>
        @endif
    </div>
</div>
@endsection
