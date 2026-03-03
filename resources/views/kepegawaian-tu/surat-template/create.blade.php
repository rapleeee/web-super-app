@extends('layouts.kepegawaian-tu')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('kepegawaian-tu.template-surat.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Template Surat Baru</h1>
            <p class="mt-1 text-gray-600">Gunakan placeholder seperti <code>{{ '{{nama}}' }}</code> atau <code>{{ '{{isi_utama}}' }}</code> sesuai kebutuhan.</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <form action="{{ route('kepegawaian-tu.template-surat.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="kode" class="mb-2 block text-sm font-medium text-gray-700">Kode Template <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" id="kode" value="{{ old('kode') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('kode') border-red-500 @enderror" placeholder="Contoh: SK-TU-01">
                </div>
                <div>
                    <label for="nama" class="mb-2 block text-sm font-medium text-gray-700">Nama Template <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('nama') border-red-500 @enderror" placeholder="Contoh: Surat Keterangan Aktif">
                </div>
            </div>

            <div>
                <label for="judul" class="mb-2 block text-sm font-medium text-gray-700">Judul Surat <span class="text-red-500">*</span></label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('judul') border-red-500 @enderror">
            </div>

            <div>
                <label for="isi_template" class="mb-2 block text-sm font-medium text-gray-700">Isi Template <span class="text-red-500">*</span></label>
                <textarea name="isi_template" id="isi_template" rows="12" class="w-full rounded-lg border border-gray-300 px-4 py-2 font-mono text-sm @error('isi_template') border-red-500 @enderror">{{ old('isi_template') }}</textarea>
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="rounded border-gray-300 text-slate-700 focus:ring-slate-500">
                Template aktif
            </label>

            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <a href="{{ route('kepegawaian-tu.template-surat.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Simpan Template</button>
            </div>
        </form>
    </div>
</div>
@endsection
