@extends('layouts.kepegawaian-tu')

@section('content')
<div class="max-w-6xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('kepegawaian-tu.arsip-digital.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Detail Arsip Digital</h1>
            <p class="mt-1 text-gray-600">{{ $tuArsipDokumen->judul }}</p>
        </div>
        <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
            v{{ $tuArsipDokumen->version }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Dokumen</h2>
            <p><span class="text-sm text-gray-500">Modul:</span> <span class="text-gray-900">{{ $tuArsipDokumen->module }}</span></p>
            <p><span class="text-sm text-gray-500">Source:</span> <span class="text-gray-900">{{ $tuArsipDokumen->source_type ?: '-' }}#{{ $tuArsipDokumen->source_id ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Judul:</span> <span class="text-gray-900">{{ $tuArsipDokumen->judul }}</span></p>
            <p><span class="text-sm text-gray-500">Nomor Dokumen:</span> <span class="text-gray-900">{{ $tuArsipDokumen->nomor_dokumen ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Tanggal Dokumen:</span> <span class="text-gray-900">{{ $tuArsipDokumen->tanggal_dokumen?->format('d M Y') ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Status Sumber:</span> <span class="text-gray-900">{{ $tuArsipDokumen->status_sumber ? ucfirst($tuArsipDokumen->status_sumber) : '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Retensi Sampai:</span> <span class="text-gray-900">{{ $tuArsipDokumen->retensi_sampai?->format('d M Y') ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Archived By:</span> <span class="text-gray-900">{{ $tuArsipDokumen->archiver?->name ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Archived At:</span> <span class="text-gray-900">{{ $tuArsipDokumen->archived_at?->format('d M Y H:i') ?: '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Updated At:</span> <span class="text-gray-900">{{ $tuArsipDokumen->updated_at?->format('d M Y H:i') ?: '-' }}</span></p>
            <div>
                <p class="text-sm text-gray-500">Tag:</p>
                @if(empty($tuArsipDokumen->tags))
                    <p class="text-gray-400 text-sm">-</p>
                @else
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($tuArsipDokumen->tags as $tag)
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Metadata</h2>
            <div class="max-h-[340px] overflow-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100">
                <pre class="whitespace-pre-wrap">{{ json_encode($tuArsipDokumen->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>

            @if($isPrivilegedUser)
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="font-semibold text-gray-900">Update Metadata Arsip</h3>
                    <form action="{{ route('kepegawaian-tu.arsip-digital.update', $tuArsipDokumen) }}" method="POST" class="mt-3 space-y-3">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Tag (pisahkan koma)</label>
                            <input type="text" name="tags" value="{{ old('tags', $tagsInput) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="surat, final, legal">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Retensi Sampai</label>
                            <input type="date" name="retensi_sampai" value="{{ old('retensi_sampai', $tuArsipDokumen->retensi_sampai?->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Catatan Versi</label>
                            <textarea name="catatan_versi" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Contoh: update tag legal dan retensi sesuai kebijakan baru.">{{ old('catatan_versi') }}</textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
                            <x-heroicon-o-check class="h-4 w-4"/>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
