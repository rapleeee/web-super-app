@extends('layouts.kepegawaian-tu')

@section('content')
<div class="max-w-5xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('kepegawaian-tu.izin-karyawan.index') }}" class="text-gray-500 hover:text-gray-700">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan Izin</h1>
            <p class="mt-1 text-gray-600">{{ $izinKaryawan->nama_karyawan }} - {{ strtoupper(str_replace('_', ' ', $izinKaryawan->jenis)) }}</p>
        </div>
        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
            {{ $izinKaryawan->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
            {{ $izinKaryawan->status === 'disetujui' ? 'bg-green-100 text-green-700' : '' }}
            {{ $izinKaryawan->status === 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
            {{ ucfirst($izinKaryawan->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Pengajuan</h3>
            <p><span class="text-sm text-gray-500">Nomor Pengajuan:</span> <span class="text-gray-900">{{ $izinKaryawan->nomor_pengajuan }}</span></p>
            <p><span class="text-sm text-gray-500">Pengaju:</span> <span class="text-gray-900">{{ $izinKaryawan->pemohon?->name ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Nama Karyawan:</span> <span class="text-gray-900">{{ $izinKaryawan->nama_karyawan }}</span></p>
            <p><span class="text-sm text-gray-500">Jenis:</span> <span class="text-gray-900">{{ strtoupper(str_replace('_', ' ', $izinKaryawan->jenis)) }}</span></p>
            <p><span class="text-sm text-gray-500">Periode:</span> <span class="text-gray-900">{{ $izinKaryawan->tanggal_mulai->format('d M Y') }} - {{ $izinKaryawan->tanggal_selesai->format('d M Y') }}</span></p>
            <p><span class="text-sm text-gray-500">Durasi:</span> <span class="text-gray-900">{{ $izinKaryawan->durasi_hari }} hari</span></p>
            @if($izinKaryawan->jenis === 'dinas_luar')
                <p><span class="text-sm text-gray-500">Hari Kegiatan:</span> <span class="text-gray-900">{{ $izinKaryawan->dinas_luar_hari ?: '-' }}</span></p>
                <p><span class="text-sm text-gray-500">Waktu Kegiatan:</span> <span class="text-gray-900">{{ $izinKaryawan->dinas_luar_waktu ?: '-' }}</span></p>
                <p><span class="text-sm text-gray-500">Tempat Kegiatan:</span> <span class="text-gray-900">{{ $izinKaryawan->dinas_luar_tempat ?: '-' }}</span></p>
            @endif
            <p><span class="text-sm text-gray-500">Menunggu:</span> <span class="text-gray-900">{{ $izinKaryawan->status === 'diajukan' ? $izinKaryawan->hari_menunggu.' hari' : '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Dibuat:</span> <span class="text-gray-900">{{ $izinKaryawan->created_at->format('d M Y H:i') }}</span></p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Hasil Approval</h3>
            <p><span class="text-sm text-gray-500">Approver:</span> <span class="text-gray-900">{{ $izinKaryawan->approver?->name ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Waktu Approval:</span> <span class="text-gray-900">{{ $izinKaryawan->approved_at?->format('d M Y H:i') ?? '-' }}</span></p>
            <p><span class="text-sm text-gray-500">Catatan:</span> <span class="text-gray-900">{{ $izinKaryawan->catatan_persetujuan ?: '-' }}</span></p>
            @if($izinKaryawan->jenis === 'dinas_luar')
                <p><span class="text-sm text-gray-500">Nomor Surat Tugas:</span> <span class="text-gray-900">{{ $izinKaryawan->surat_tugas_nomor ?: '-' }}</span></p>
                <p><span class="text-sm text-gray-500">Sebagai:</span> <span class="text-gray-900">{{ $izinKaryawan->surat_tugas_sebagai ?: '-' }}</span></p>
            @endif
            <div>
                <p class="text-sm text-gray-500">Lampiran:</p>
                @if($izinKaryawan->lampiran)
                    <a href="{{ Storage::url($izinKaryawan->lampiran) }}" target="_blank" class="text-blue-600 hover:underline text-sm">Lihat Lampiran</a>
                @else
                    <p class="text-gray-400 text-sm">-</p>
                @endif
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm space-y-3">
        <h3 class="text-lg font-semibold text-gray-900">Alasan Pengajuan</h3>
        <p class="text-gray-700">{{ $izinKaryawan->alasan }}</p>
    </div>

    @if(in_array(auth()->user()->role, ['admin', 'pejabat'], true) && $izinKaryawan->status === 'diajukan')
        <div class="rounded-xl bg-white p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Approval Admin</h3>
            <form action="{{ route('kepegawaian-tu.izin-karyawan.approval', $izinKaryawan) }}" method="POST" class="space-y-4"
                  x-data="{ statusApproval: @js(old('status', 'disetujui')) }">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Keputusan</label>
                    <select name="status" id="status" x-model="statusApproval" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('status') border-red-500 @enderror">
                        <option value="disetujui" {{ old('status', 'disetujui') === 'disetujui' ? 'selected' : '' }}>Setujui</option>
                        <option value="ditolak" {{ old('status') === 'ditolak' ? 'selected' : '' }}>Tolak</option>
                    </select>
                </div>

                @if($izinKaryawan->jenis === 'dinas_luar')
                    <div x-show="statusApproval === 'disetujui'" x-cloak class="grid grid-cols-1 gap-4 rounded-lg border border-emerald-100 bg-emerald-50 p-4 sm:grid-cols-2">
                        <div>
                            <label for="surat_tugas_nomor" class="mb-2 block text-sm font-medium text-gray-700">Nomor Surat Tugas <span class="text-red-500">*</span></label>
                            <input type="text" name="surat_tugas_nomor" id="surat_tugas_nomor" value="{{ old('surat_tugas_nomor') }}" placeholder="Contoh: 090/ST-TU/III/2026" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('surat_tugas_nomor') border-red-500 @enderror">
                            @error('surat_tugas_nomor')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="surat_tugas_sebagai" class="mb-2 block text-sm font-medium text-gray-700">Sebagai <span class="text-red-500">*</span></label>
                            <textarea name="surat_tugas_sebagai" id="surat_tugas_sebagai" rows="2" placeholder="Contoh: Peserta Rapat Koordinasi Sarpras" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('surat_tugas_sebagai') border-red-500 @enderror">{{ old('surat_tugas_sebagai') }}</textarea>
                            @error('surat_tugas_sebagai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div>
                    <label for="catatan_persetujuan" class="mb-2 block text-sm font-medium text-gray-700">Catatan Persetujuan</label>
                    <textarea name="catatan_persetujuan" id="catatan_persetujuan" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 @error('catatan_persetujuan') border-red-500 @enderror">{{ old('catatan_persetujuan') }}</textarea>
                </div>

                <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Proses Approval</button>
            </form>
        </div>
    @endif

    @if($izinKaryawan->hasSuratTugas())
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <a href="{{ route('kepegawaian-tu.izin-karyawan.surat-tugas', $izinKaryawan) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">
                <x-heroicon-o-printer class="w-4 h-4"/>
                Cetak / Simpan PDF Surat Tugas
            </a>
        </div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm flex items-center justify-end gap-3">
        @if(($izinKaryawan->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'pejabat'], true)) && $izinKaryawan->status === 'diajukan')
            <a href="{{ route('kepegawaian-tu.izin-karyawan.edit', $izinKaryawan) }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
                <x-heroicon-o-pencil-square class="w-4 h-4"/>
                Edit
            </a>
            <form id="delete-izin" action="{{ route('kepegawaian-tu.izin-karyawan.destroy', $izinKaryawan) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-izin', 'Hapus Pengajuan?', 'Pengajuan izin akan dihapus permanen!')" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 transition">
                    <x-heroicon-o-trash class="w-4 h-4"/>
                    Hapus
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
