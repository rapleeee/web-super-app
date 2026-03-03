@extends('layouts.kepegawaian-tu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Surat Menyurat TU</h1>
            <p class="mt-1 text-gray-600">Workflow surat: draft, review, final, dan arsip.</p>
        </div>
        <a href="{{ route('kepegawaian-tu.surat.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
            <x-heroicon-o-plus class="w-4 h-4"/>
            Buat Surat
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-gray-700">{{ $summary['draft'] }}</p>
            <p class="text-sm text-gray-500">Draft</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-violet-700">{{ $summary['review'] }}</p>
            <p class="text-sm text-gray-500">Review</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-emerald-700">{{ $summary['final'] }}</p>
            <p class="text-sm text-gray-500">Final</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-2xl font-bold text-blue-700">{{ $summary['arsip'] }}</p>
            <p class="text-sm text-gray-500">Arsip</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm">
        <form action="{{ route('kepegawaian-tu.surat.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor/perihal/tujuan..." class="rounded-lg border border-gray-300 px-4 py-2 text-sm min-w-[220px]">
            <select name="status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
                <option value="final" {{ request('status') === 'final' ? 'selected' : '' }}>Final</option>
                <option value="arsip" {{ request('status') === 'arsip' ? 'selected' : '' }}>Arsip</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Filter</button>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('kepegawaian-tu.surat.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Nomor</th>
                        <th class="px-6 py-3 text-left">Perihal</th>
                        <th class="px-6 py-3 text-left">Tujuan</th>
                        <th class="px-6 py-3 text-left">Pembuat</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($surats as $surat)
                        <tr>
                            <td class="px-6 py-4 text-gray-700">{{ $surat->nomor_surat ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $surat->perihal }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $surat->tujuan }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $surat->creator?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $surat->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $surat->status === 'review' ? 'bg-violet-100 text-violet-700' : '' }}
                                    {{ $surat->status === 'final' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $surat->status === 'arsip' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ ucfirst($surat->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('kepegawaian-tu.surat.show', $surat) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    @if(in_array($surat->status, ['final', 'arsip'], true))
                                        <a href="{{ route('kepegawaian-tu.surat.print', $surat) }}" class="text-indigo-600 hover:text-indigo-800" title="Cetak">
                                            <x-heroicon-o-printer class="w-5 h-5"/>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                {{ $isPrivilegedUser ? 'Belum ada data surat.' : 'Belum ada surat yang Anda buat.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($surats->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $surats->links() }}</div>
        @endif
    </div>
</div>
@endsection
