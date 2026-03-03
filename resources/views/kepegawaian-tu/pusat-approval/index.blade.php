@extends('layouts.kepegawaian-tu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pusat Approval</h1>
            <p class="mt-1 text-gray-600">Satu halaman untuk memproses pengajuan izin dan memantau dokumen final masuk.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-violet-600">{{ $kpi['surat_review'] }}</p>
            <p class="text-sm text-gray-500">Surat Menunggu Finalisasi</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-amber-600">{{ $kpi['pending'] }}</p>
            <p class="text-sm text-gray-500">Pengajuan Menunggu Approval</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-red-600">{{ $kpi['overdue_sla'] }}</p>
            <p class="text-sm text-gray-500">Lewat SLA (>{{ $slaHari }} hari)</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-emerald-600">{{ $kpi['approval_hari_ini'] }}</p>
            <p class="text-sm text-gray-500">Approval Selesai Hari Ini</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-3xl font-bold text-blue-600">{{ $kpi['dokumen_final_hari_ini'] }}</p>
            <p class="text-sm text-gray-500">Dokumen Final Masuk Hari Ini</p>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Antrian Finalisasi Surat</h2>
            <a href="{{ route('kepegawaian-tu.surat.index', ['status' => 'review']) }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Perihal</th>
                        <th class="px-6 py-3 text-left">Template</th>
                        <th class="px-6 py-3 text-left">Pembuat</th>
                        <th class="px-6 py-3 text-left">Update</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratReviewQueue as $surat)
                        <tr>
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $surat->perihal }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $surat->template?->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $surat->creator?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $surat->updated_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kepegawaian-tu.surat.show', $surat) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                                        Detail
                                    </a>
                                    <form action="{{ route('kepegawaian-tu.surat.approve-final', $surat) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="tanggal_surat" value="{{ now()->toDateString() }}">
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-violet-600 px-3 py-1.5 text-xs text-white hover:bg-violet-700 transition">
                                            Finalkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada surat yang menunggu finalisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Antrian Approval Izin</h2>
            <a href="{{ route('kepegawaian-tu.izin-karyawan.index', ['status' => 'diajukan']) }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Nomor</th>
                        <th class="px-6 py-3 text-left">Karyawan</th>
                        <th class="px-6 py-3 text-left">Jenis</th>
                        <th class="px-6 py-3 text-left">Periode</th>
                        <th class="px-6 py-3 text-center">Menunggu</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pendingIzin as $izin)
                        <tr class="{{ $izin->hari_menunggu > $slaHari ? 'bg-red-50/40' : '' }}">
                            <td class="px-6 py-4 text-gray-700">{{ $izin->nomor_pengajuan }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $izin->nama_karyawan }}</p>
                                <p class="text-xs text-gray-500">Pengaju: {{ $izin->pemohon?->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ strtoupper(str_replace('_', ' ', $izin->jenis)) }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $izin->tanggal_mulai->format('d M Y') }} - {{ $izin->tanggal_selesai->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $izin->hari_menunggu > $slaHari ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $izin->hari_menunggu }} hari
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kepegawaian-tu.izin-karyawan.show', $izin) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                                        Detail
                                    </a>
                                    <form action="{{ route('kepegawaian-tu.izin-karyawan.approval', $izin) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <input type="hidden" name="catatan_persetujuan" value="Disetujui melalui pusat approval.">
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs text-white hover:bg-emerald-700 transition">
                                            Setujui Cepat
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada pengajuan yang menunggu approval.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingIzin->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $pendingIzin->links() }}</div>
        @endif
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Dokumen Final Terbaru</h2>
            <a href="{{ route('kepegawaian-tu.berita-acara-final.index') }}" class="text-sm text-blue-600 hover:underline">Buka Inbox Final</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Waktu Masuk</th>
                        <th class="px-6 py-3 text-left">Sumber</th>
                        <th class="px-6 py-3 text-left">Nama Guru</th>
                        <th class="px-6 py-3 text-left">Lokasi</th>
                        <th class="px-6 py-3 text-left">Petugas</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dokumenFinalTerbaru as $dokumen)
                        <tr>
                            <td class="px-6 py-4 text-gray-700">{{ $dokumen['created_at']->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $dokumen['sumber'] === 'Laboran' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $dokumen['sumber'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-900">{{ $dokumen['nama_guru'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $dokumen['lokasi'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $dokumen['petugas'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ $dokumen['route_detail'] }}" class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                    <x-heroicon-o-eye class="w-5 h-5 inline"/>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada dokumen final terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
