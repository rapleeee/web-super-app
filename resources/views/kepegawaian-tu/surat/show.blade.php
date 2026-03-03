@extends('layouts.kepegawaian-tu')

@section('content')
@php
    $isOwner = $tuSurat->created_by === auth()->id();
@endphp
<div class="max-w-5xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('kepegawaian-tu.surat.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Detail Surat</h1>
            <p class="mt-1 text-gray-600">{{ $tuSurat->perihal }}</p>
        </div>
        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
            {{ $tuSurat->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
            {{ $tuSurat->status === 'review' ? 'bg-violet-100 text-violet-700' : '' }}
            {{ $tuSurat->status === 'final' ? 'bg-emerald-100 text-emerald-700' : '' }}
            {{ $tuSurat->status === 'arsip' ? 'bg-blue-100 text-blue-700' : '' }}">
            {{ ucfirst($tuSurat->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Surat</h3>
            <p><span class="text-sm text-gray-500">Nomor Surat:</span> <span class="text-gray-900">{{ $tuSurat->nomor_surat ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Template:</span> <span class="text-gray-900">{{ $tuSurat->template?->nama ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Perihal:</span> <span class="text-gray-900">{{ $tuSurat->perihal }}</span></p>
            <p><span class="text-sm text-gray-500">Tujuan:</span> <span class="text-gray-900">{{ $tuSurat->tujuan }}</span></p>
            <p><span class="text-sm text-gray-500">Tanggal Surat:</span> <span class="text-gray-900">{{ $tuSurat->tanggal_surat?->format('d M Y') ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Dibuat Oleh:</span> <span class="text-gray-900">{{ $tuSurat->creator?->name ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Disetujui Oleh:</span> <span class="text-gray-900">{{ $tuSurat->approver?->name ?? '-' }}</span></p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Workflow</h3>
            <p><span class="text-sm text-gray-500">Diajukan Review:</span> <span class="text-gray-900">{{ $tuSurat->status !== 'draft' ? 'Ya' : 'Belum' }}</span></p>
            <p><span class="text-sm text-gray-500">Finalized At:</span> <span class="text-gray-900">{{ $tuSurat->finalized_at?->format('d M Y H:i') ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Archived At:</span> <span class="text-gray-900">{{ $tuSurat->archived_at?->format('d M Y H:i') ?? '-' }}</span></p>
            @if(in_array($tuSurat->status, ['final', 'arsip'], true) && $tuSurat->verification_token)
                <div>
                    <p class="text-sm text-gray-500">Link Verifikasi:</p>
                    <a href="{{ route('kepegawaian-tu.surat.verify', ['tuSurat' => $tuSurat, 'token' => $tuSurat->verification_token]) }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all">Buka Verifikasi Surat</a>
                </div>
            @endif
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
        <h3 class="text-lg font-semibold text-gray-900">Isi Surat</h3>
        <pre class="whitespace-pre-wrap text-sm text-gray-700 font-sans">{{ $tuSurat->isi_surat }}</pre>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm flex flex-wrap items-center justify-end gap-2">
        @if($tuSurat->status === 'draft' && ($isOwner || $isPrivilegedUser))
            <a href="{{ route('kepegawaian-tu.surat.edit', $tuSurat) }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
                <x-heroicon-o-pencil-square class="w-4 h-4"/>
                Edit Draft
            </a>

            <form action="{{ route('kepegawaian-tu.surat.submit-review', $tuSurat) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-white hover:bg-violet-700 transition">
                    <x-heroicon-o-paper-airplane class="w-4 h-4"/>
                    Ajukan Review
                </button>
            </form>

            <form id="delete-surat" action="{{ route('kepegawaian-tu.surat.destroy', $tuSurat) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-surat', 'Hapus Draft?', 'Draft surat akan dihapus permanen!')" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-4 h-4"/>
                    Hapus Draft
                </button>
            </form>
        @endif

        @if($tuSurat->status === 'review' && $isPrivilegedUser)
            <form action="{{ route('kepegawaian-tu.surat.approve-final', $tuSurat) }}" method="POST" class="inline-flex items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="tanggal_surat" value="{{ now()->toDateString() }}">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700 transition">
                    <x-heroicon-o-check-badge class="w-4 h-4"/>
                    Finalkan Surat
                </button>
            </form>
        @endif

        @if(in_array($tuSurat->status, ['final', 'arsip'], true))
            <a href="{{ route('kepegawaian-tu.surat.print', $tuSurat) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">
                <x-heroicon-o-printer class="w-4 h-4"/>
                Cetak / Simpan PDF
            </a>
        @endif

        @if($tuSurat->status === 'final' && $isPrivilegedUser)
            <form action="{{ route('kepegawaian-tu.surat.archive', $tuSurat) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition">
                    <x-heroicon-o-archive-box class="w-4 h-4"/>
                    Arsipkan
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
