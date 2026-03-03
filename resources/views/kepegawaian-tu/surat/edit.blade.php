@extends('layouts.kepegawaian-tu')

@section('content')
<div class="max-w-5xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('kepegawaian-tu.surat.show', $tuSurat) }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Draft Surat</h1>
            <p class="mt-1 text-gray-600">Draft hanya dapat diubah sebelum diajukan review.</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <form action="{{ route('kepegawaian-tu.surat.update', $tuSurat) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="tu_surat_template_id" class="mb-2 block text-sm font-medium text-gray-700">Template Surat</label>
                    <select name="tu_surat_template_id" id="tu_surat_template_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tu_surat_template_id') border-red-500 @enderror">
                        <option value="">Tanpa Template</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ (string) old('tu_surat_template_id', $tuSurat->tu_surat_template_id) === (string) $template->id ? 'selected' : '' }}>{{ $template->kode }} - {{ $template->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tanggal_surat" class="mb-2 block text-sm font-medium text-gray-700">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', $tuSurat->tanggal_surat?->toDateString() ?? now()->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tanggal_surat') border-red-500 @enderror">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="perihal" class="mb-2 block text-sm font-medium text-gray-700">Perihal <span class="text-red-500">*</span></label>
                    <input type="text" name="perihal" id="perihal" value="{{ old('perihal', $tuSurat->perihal) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('perihal') border-red-500 @enderror">
                </div>
                <div>
                    <label for="tujuan" class="mb-2 block text-sm font-medium text-gray-700">Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="tujuan" id="tujuan" value="{{ old('tujuan', $tuSurat->tujuan) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('tujuan') border-red-500 @enderror">
                </div>
            </div>

            <div>
                <label for="isi_surat" class="mb-2 block text-sm font-medium text-gray-700">Isi Surat <span class="text-red-500">*</span></label>
                <textarea name="isi_surat" id="isi_surat" rows="16" class="w-full rounded-lg border border-gray-300 px-4 py-2 font-mono text-sm @error('isi_surat') border-red-500 @enderror">{{ old('isi_surat', $tuSurat->isi_surat) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <a href="{{ route('kepegawaian-tu.surat.show', $tuSurat) }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const templateMap = @json($templates->mapWithKeys(fn ($template) => [(string) $template->id => $template->isi_template]));
        const templateSelect = document.getElementById('tu_surat_template_id');
        const isiSuratField = document.getElementById('isi_surat');

        if (!templateSelect || !isiSuratField) {
            return;
        }

        templateSelect.addEventListener('change', () => {
            const selectedTemplate = templateMap[templateSelect.value] ?? '';

            if (!selectedTemplate) {
                return;
            }

            Swal.fire({
                title: 'Terapkan Template?',
                text: 'Isi surat akan diganti dengan isi template terpilih.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, terapkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#334155',
                cancelButtonColor: '#6b7280',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg px-4 py-2',
                    cancelButton: 'rounded-lg px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    isiSuratField.value = selectedTemplate;
                }
            });
        });
    });
</script>
@endpush
