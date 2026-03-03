@extends('layouts.kepegawaian-tu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Kepegawaian TU</h1>
            <p class="mt-1 text-gray-600">Pusat administrasi surat, izin karyawan, dan dokumen final lintas modul.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($isPrivilegedUser)
                <a href="{{ route('kepegawaian-tu.pusat-approval.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50 transition">
                    <x-heroicon-o-clipboard-document-check class="w-4 h-4"/>
                    Pusat Approval
                </a>
            @endif
            <a href="{{ route('kepegawaian-tu.izin-karyawan.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
                <x-heroicon-o-plus class="w-4 h-4"/>
                Ajukan Izin
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-amber-600">{{ $izinPending }}</p>
            <p class="text-sm text-gray-500">Pengajuan Menunggu Approval</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-orange-600">{{ $izinPendingLebih3Hari }}</p>
            <p class="text-sm text-gray-500">Pending Lewat SLA (>{{ $slaHari }} hari)</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-indigo-600">{{ $izinDiajukanHariIni }}</p>
            <p class="text-sm text-gray-500">Pengajuan Baru Hari Ini</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-emerald-600">{{ $approvalSelesaiHariIni }}</p>
            <p class="text-sm text-gray-500">Approval Selesai Hari Ini</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-green-600">{{ $izinDisetujuiBulanIni }}</p>
            <p class="text-sm text-gray-500">Izin Disetujui Bulan Ini</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-rose-600">{{ $izinDitolakBulanIni }}</p>
            <p class="text-sm text-gray-500">Izin Ditolak Bulan Ini</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-blue-600">{{ $totalBeritaAcaraFinal }}</p>
            <p class="text-sm text-gray-500">Dokumen Berita Acara Final</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-cyan-600">{{ $dokumenFinalMasukHariIni }}</p>
            <p class="text-sm text-gray-500">Dokumen Final Masuk Hari Ini</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-slate-700">{{ $suratDraft }}</p>
            <p class="text-sm text-gray-500">Surat Status Draft</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-violet-700">{{ $suratReview }}</p>
            <p class="text-sm text-gray-500">Surat Menunggu Review</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-emerald-700">{{ $suratFinal }}</p>
            <p class="text-sm text-gray-500">Surat Final</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-sky-700">{{ $arsipDigital }}</p>
            <p class="text-sm text-gray-500">Dokumen Arsip Digital</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden lg:col-span-1">
            <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Akses Cepat</h2>
            </div>
            <div class="p-5 grid grid-cols-1 gap-3">
                @if($isPrivilegedUser)
                    <a href="{{ route('kepegawaian-tu.pusat-approval.index') }}" class="rounded-lg border border-gray-200 px-4 py-3 hover:border-slate-300 hover:bg-gray-50 transition">
                        <p class="font-medium text-gray-900">Pusat Approval</p>
                        <p class="text-xs text-gray-500 mt-1">Kelola pengajuan menunggu approval dari satu halaman.</p>
                    </a>
                @endif
                <a href="{{ route('kepegawaian-tu.surat.index') }}" class="rounded-lg border border-gray-200 px-4 py-3 hover:border-slate-300 hover:bg-gray-50 transition">
                    <p class="font-medium text-gray-900">Surat Menyurat</p>
                    <p class="text-xs text-gray-500 mt-1">Kelola draft, review, final, arsip, dan cetak surat.</p>
                </a>
                @if($isPrivilegedUser)
                    <a href="{{ route('kepegawaian-tu.template-surat.index') }}" class="rounded-lg border border-gray-200 px-4 py-3 hover:border-slate-300 hover:bg-gray-50 transition">
                        <p class="font-medium text-gray-900">Template Surat</p>
                        <p class="text-xs text-gray-500 mt-1">Atur template dinamis untuk format surat resmi TU.</p>
                    </a>
                @endif
                <a href="{{ route('kepegawaian-tu.berita-acara-final.index') }}" class="rounded-lg border border-gray-200 px-4 py-3 hover:border-slate-300 hover:bg-gray-50 transition">
                    <p class="font-medium text-gray-900">Inbox Berita Acara Final</p>
                    <p class="text-xs text-gray-500 mt-1">Tarik data final dari Laboran dan Sarana Umum.</p>
                </a>
                <a href="{{ route('kepegawaian-tu.arsip-digital.index') }}" class="rounded-lg border border-gray-200 px-4 py-3 hover:border-slate-300 hover:bg-gray-50 transition">
                    <p class="font-medium text-gray-900">Arsip Digital</p>
                    <p class="text-xs text-gray-500 mt-1">Kelola tagging, retensi, dan versi dokumen terarsip.</p>
                </a>
                <a href="{{ route('kepegawaian-tu.izin-karyawan.index') }}" class="rounded-lg border border-gray-200 px-4 py-3 hover:border-slate-300 hover:bg-gray-50 transition">
                    <p class="font-medium text-gray-900">Izin Karyawan</p>
                    <p class="text-xs text-gray-500 mt-1">Kelola status pengajuan: diajukan, disetujui, ditolak.</p>
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden lg:col-span-2">
            <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Prioritas Tindakan</h2>
                @if($isPrivilegedUser)
                    <a href="{{ route('kepegawaian-tu.pusat-approval.index') }}" class="text-sm text-blue-600 hover:underline">Buka Pusat Approval</a>
                @endif
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($prioritasIzin as $izin)
                    <a href="{{ route('kepegawaian-tu.izin-karyawan.show', $izin) }}" class="block px-5 py-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $izin->nama_karyawan }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ strtoupper(str_replace('_', ' ', $izin->jenis)) }} • Pengaju: {{ $izin->pemohon?->name ?? '-' }}</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-sm font-semibold {{ $izin->hari_menunggu > $slaHari ? 'text-red-600' : 'text-amber-600' }}">
                                    Menunggu {{ $izin->hari_menunggu }} hari
                                </p>
                                <p class="text-xs text-gray-500">{{ $izin->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-gray-500">Tidak ada pengajuan yang perlu ditindaklanjuti.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Pengajuan Izin Terbaru</h2>
            <a href="{{ route('kepegawaian-tu.izin-karyawan.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentIzin as $izin)
                <a href="{{ route('kepegawaian-tu.izin-karyawan.show', $izin) }}" class="block px-5 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $izin->nama_karyawan }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ strtoupper(str_replace('_', ' ', $izin->jenis)) }} • {{ $izin->tanggal_mulai->format('d M Y') }} - {{ $izin->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                            {{ $izin->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $izin->status === 'disetujui' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $izin->status === 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($izin->status) }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-5 py-10 text-center text-sm text-gray-500">Belum ada pengajuan izin.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
