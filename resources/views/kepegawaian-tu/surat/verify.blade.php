<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Surat TU</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 p-6">
<div class="mx-auto max-w-3xl rounded-2xl border border-emerald-200 bg-white p-8 shadow-sm">
    <div class="mb-6 flex items-center gap-3">
        <div class="rounded-full bg-emerald-100 p-2 text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dokumen Terverifikasi</h1>
            <p class="text-sm text-slate-600">Surat ini valid dan tercatat pada sistem Kepegawaian TU.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
        <div class="rounded-xl border border-slate-200 p-4">
            <p class="text-slate-500">Nomor Surat</p>
            <p class="font-semibold text-slate-900">{{ $tuSurat->nomor_surat }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 p-4">
            <p class="text-slate-500">Status</p>
            <p class="font-semibold text-slate-900 uppercase">{{ $tuSurat->status }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 p-4">
            <p class="text-slate-500">Tanggal Surat</p>
            <p class="font-semibold text-slate-900">{{ $tuSurat->tanggal_surat?->format('d M Y') ?? '-' }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 p-4">
            <p class="text-slate-500">Disetujui Oleh</p>
            <p class="font-semibold text-slate-900">{{ $tuSurat->approver?->name ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-xl bg-slate-50 p-4 text-sm">
        <p class="text-slate-500">Perihal</p>
        <p class="font-semibold text-slate-900">{{ $tuSurat->perihal }}</p>
        <p class="mt-3 text-slate-500">Tujuan</p>
        <p class="font-semibold text-slate-900">{{ $tuSurat->tujuan }}</p>
    </div>
</div>
</body>
</html>
