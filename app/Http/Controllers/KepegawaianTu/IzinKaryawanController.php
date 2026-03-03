<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepegawaianTu\ApprovalIzinKaryawanRequest;
use App\Http\Requests\KepegawaianTu\StoreIzinKaryawanRequest;
use App\Http\Requests\KepegawaianTu\UpdateIzinKaryawanRequest;
use App\Models\AuditLog;
use App\Models\IzinKaryawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IzinKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $slaHari = max((int) config('kepegawaian_tu.izin.sla_hari', 3), 1);
        $isPrivilegedUser = $this->isPrivilegedAdmin();

        $query = IzinKaryawan::query()
            ->with(['pemohon', 'approver'])
            ->when(! $isPrivilegedUser, fn ($query) => $query->where('user_id', auth()->id()))
            ->when($request->search, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_karyawan', 'like', "%{$search}%")
                        ->orWhere('alasan', 'like', "%{$search}%");
                });
            })
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->jenis, fn ($query, $jenis) => $query->where('jenis', $jenis))
            ->when($request->tanggal_mulai, fn ($query, $tanggalMulai) => $query->whereDate('tanggal_mulai', '>=', $tanggalMulai))
            ->when($request->tanggal_selesai, fn ($query, $tanggalSelesai) => $query->whereDate('tanggal_selesai', '<=', $tanggalSelesai))
            ->latest('created_at');

        $izins = $query->paginate(12)->withQueryString();
        $statsSource = IzinKaryawan::query()
            ->when(! $isPrivilegedUser, fn ($query) => $query->where('user_id', auth()->id()));

        $stats = [
            'diajukan' => (clone $statsSource)->where('status', 'diajukan')->count(),
            'disetujui' => (clone $statsSource)->where('status', 'disetujui')->count(),
            'ditolak' => (clone $statsSource)->where('status', 'ditolak')->count(),
            'overdue_sla' => (clone $statsSource)
                ->where('status', 'diajukan')
                ->whereDate('created_at', '<', now()->subDays($slaHari)->toDateString())
                ->count(),
        ];

        return view('kepegawaian-tu.izin-karyawan.index', compact('izins', 'stats', 'slaHari', 'isPrivilegedUser'));
    }

    public function export(Request $request): Response
    {
        $isPrivilegedUser = $this->isPrivilegedAdmin();

        $rows = IzinKaryawan::query()
            ->with(['pemohon', 'approver'])
            ->when(! $isPrivilegedUser, fn ($query) => $query->where('user_id', auth()->id()))
            ->when($request->search, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_karyawan', 'like', "%{$search}%")
                        ->orWhere('alasan', 'like', "%{$search}%");
                });
            })
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->jenis, fn ($query, $jenis) => $query->where('jenis', $jenis))
            ->when($request->tanggal_mulai, fn ($query, $tanggalMulai) => $query->whereDate('tanggal_mulai', '>=', $tanggalMulai))
            ->when($request->tanggal_selesai, fn ($query, $tanggalSelesai) => $query->whereDate('tanggal_selesai', '<=', $tanggalSelesai))
            ->latest('created_at')
            ->get();

        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['Nomor', 'Nama Karyawan', 'Jenis', 'Periode', 'Durasi Hari', 'Status', 'Pengaju', 'Approver', 'Alasan']);
        foreach ($rows as $row) {
            fputcsv($output, [
                $row->nomor_pengajuan,
                $row->nama_karyawan,
                strtoupper(str_replace('_', ' ', $row->jenis)),
                $row->tanggal_mulai->format('Y-m-d').' s/d '.$row->tanggal_selesai->format('Y-m-d'),
                $row->durasi_hari,
                $row->status,
                $row->pemohon?->name ?? '-',
                $row->approver?->name ?? '-',
                $row->alasan,
            ]);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        AuditLog::record('tu-izin-karyawan', 'export', null, null, [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'jenis' => $request->string('jenis')->toString(),
            'tanggal_mulai' => $request->string('tanggal_mulai')->toString(),
            'tanggal_selesai' => $request->string('tanggal_selesai')->toString(),
            'exported_rows' => $rows->count(),
        ]);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="tu-izin-karyawan.csv"');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('kepegawaian-tu.izin-karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIzinKaryawanRequest $request): RedirectResponse
    {
        $data = $this->sanitizeDinasLuarPayload($request->validated());
        $data['user_id'] = auth()->id();
        $data['status'] = 'diajukan';

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('kepegawaian-tu/izin', 'public');
        }

        $izin = IzinKaryawan::query()->create($data);
        AuditLog::record('tu-izin-karyawan', 'create', $izin, null, $izin->toArray());

        return redirect()
            ->route('kepegawaian-tu.izin-karyawan.index')
            ->with('success', 'Pengajuan izin berhasil dibuat dan menunggu approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IzinKaryawan $izinKaryawan): View
    {
        $this->authorizeRead($izinKaryawan);
        $izinKaryawan->load(['pemohon', 'approver']);

        return view('kepegawaian-tu.izin-karyawan.show', compact('izinKaryawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IzinKaryawan $izinKaryawan): View
    {
        $this->authorizeModification($izinKaryawan);

        return view('kepegawaian-tu.izin-karyawan.edit', compact('izinKaryawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIzinKaryawanRequest $request, IzinKaryawan $izinKaryawan): RedirectResponse
    {
        $this->authorizeModification($izinKaryawan);
        $before = $izinKaryawan->toArray();
        $data = $this->sanitizeDinasLuarPayload($request->validated());

        if ($request->hasFile('lampiran')) {
            if ($izinKaryawan->lampiran) {
                Storage::disk('public')->delete($izinKaryawan->lampiran);
            }
            $data['lampiran'] = $request->file('lampiran')->store('kepegawaian-tu/izin', 'public');
        }

        $izinKaryawan->update($data);
        AuditLog::record('tu-izin-karyawan', 'update', $izinKaryawan, $before, $izinKaryawan->fresh()?->toArray());

        return redirect()
            ->route('kepegawaian-tu.izin-karyawan.show', $izinKaryawan)
            ->with('success', 'Pengajuan izin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IzinKaryawan $izinKaryawan): RedirectResponse
    {
        $this->authorizeModification($izinKaryawan);
        $before = $izinKaryawan->toArray();

        if ($izinKaryawan->lampiran) {
            Storage::disk('public')->delete($izinKaryawan->lampiran);
        }

        $izinKaryawan->delete();
        AuditLog::record('tu-izin-karyawan', 'delete', null, $before, null);

        return redirect()
            ->route('kepegawaian-tu.izin-karyawan.index')
            ->with('success', 'Pengajuan izin berhasil dihapus.');
    }

    public function approval(ApprovalIzinKaryawanRequest $request, IzinKaryawan $izinKaryawan): RedirectResponse
    {
        abort_unless($this->isPrivilegedAdmin(), 403, 'Hanya admin/pejabat yang dapat melakukan approval.');

        if ($izinKaryawan->status !== 'diajukan') {
            return redirect()
                ->route('kepegawaian-tu.izin-karyawan.show', $izinKaryawan)
                ->with('warning', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $before = $izinKaryawan->toArray();
        $validated = $request->validated();

        $approvalData = [
            'status' => $validated['status'],
            'catatan_persetujuan' => $validated['catatan_persetujuan'] ?? null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ];

        if ($izinKaryawan->jenis === 'dinas_luar' && $validated['status'] === 'disetujui') {
            $approvalData['surat_tugas_nomor'] = trim((string) ($validated['surat_tugas_nomor'] ?? ''));
            $approvalData['surat_tugas_sebagai'] = trim((string) ($validated['surat_tugas_sebagai'] ?? ''));
            $approvalData['surat_tugas_diterbitkan_at'] = now();
            $approvalData['surat_tugas_signed_at'] = now();
            $approvalData['surat_tugas_signature_token'] = hash(
                'sha256',
                $izinKaryawan->id.'|'.(string) auth()->id().'|'.Str::uuid()->toString()
            );
        } else {
            $approvalData['surat_tugas_nomor'] = null;
            $approvalData['surat_tugas_sebagai'] = null;
            $approvalData['surat_tugas_diterbitkan_at'] = null;
            $approvalData['surat_tugas_signed_at'] = null;
            $approvalData['surat_tugas_signature_token'] = null;
        }

        $izinKaryawan->update([
            ...$approvalData,
        ]);

        AuditLog::record('tu-izin-karyawan', 'approval', $izinKaryawan, $before, $izinKaryawan->fresh()?->toArray());

        return redirect()
            ->route('kepegawaian-tu.izin-karyawan.show', $izinKaryawan)
            ->with('success', 'Status approval berhasil diperbarui.');
    }

    public function suratTugas(IzinKaryawan $izinKaryawan): View
    {
        $this->authorizeRead($izinKaryawan);
        abort_unless($izinKaryawan->hasSuratTugas(), 404);

        $izinKaryawan->load(['pemohon', 'approver']);

        return view('kepegawaian-tu.izin-karyawan.surat-tugas', [
            'izinKaryawan' => $izinKaryawan,
            'instansi' => config('kepegawaian_tu.surat_tugas.instansi'),
            'kepalaSekolah' => config('kepegawaian_tu.surat_tugas.kepala_sekolah'),
            'jabatanPenandatangan' => config('kepegawaian_tu.surat_tugas.jabatan_penandatangan'),
        ]);
    }

    private function authorizeModification(IzinKaryawan $izinKaryawan): void
    {
        $isAdmin = $this->isPrivilegedAdmin();
        $isOwner = $izinKaryawan->user_id === auth()->id();

        abort_if(! $isAdmin && ! $isOwner, 403);
        abort_if($izinKaryawan->status !== 'diajukan', 403, 'Pengajuan yang sudah diproses tidak bisa diubah.');
    }

    private function authorizeRead(IzinKaryawan $izinKaryawan): void
    {
        if ($this->isPrivilegedAdmin()) {
            return;
        }

        abort_if($izinKaryawan->user_id !== auth()->id(), 403);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sanitizeDinasLuarPayload(array $data): array
    {
        if (($data['jenis'] ?? null) !== 'dinas_luar') {
            $data['dinas_luar_hari'] = null;
            $data['dinas_luar_waktu'] = null;
            $data['dinas_luar_tempat'] = null;
        }

        return $data;
    }

    private function isPrivilegedAdmin(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'pejabat'], true);
    }
}
