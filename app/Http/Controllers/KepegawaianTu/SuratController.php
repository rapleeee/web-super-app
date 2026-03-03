<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepegawaianTu\FinalizeTuSuratRequest;
use App\Http\Requests\KepegawaianTu\StoreTuSuratRequest;
use App\Http\Requests\KepegawaianTu\UpdateTuSuratRequest;
use App\Models\AuditLog;
use App\Models\TuSurat;
use App\Models\TuSuratTemplate;
use App\Services\KepegawaianTu\ArsipDokumenService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function __construct(private ArsipDokumenService $arsipDokumenService) {}

    public function index(Request $request): View
    {
        $isPrivilegedUser = $this->isPrivilegedUser();

        $surats = TuSurat::query()
            ->with(['template', 'creator', 'reviewer', 'approver'])
            ->when(! $isPrivilegedUser, fn ($query) => $query->where('created_by', auth()->id()))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->search, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%")
                        ->orWhere('tujuan', 'like', "%{$search}%");
                });
            })
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $summary = [
            'draft' => TuSurat::query()->when(! $isPrivilegedUser, fn ($query) => $query->where('created_by', auth()->id()))->where('status', 'draft')->count(),
            'review' => TuSurat::query()->when(! $isPrivilegedUser, fn ($query) => $query->where('created_by', auth()->id()))->where('status', 'review')->count(),
            'final' => TuSurat::query()->when(! $isPrivilegedUser, fn ($query) => $query->where('created_by', auth()->id()))->where('status', 'final')->count(),
            'arsip' => TuSurat::query()->when(! $isPrivilegedUser, fn ($query) => $query->where('created_by', auth()->id()))->where('status', 'arsip')->count(),
        ];

        return view('kepegawaian-tu.surat.index', compact('surats', 'summary', 'isPrivilegedUser'));
    }

    public function create(): View
    {
        $templates = TuSuratTemplate::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('kepegawaian-tu.surat.create', compact('templates'));
    }

    public function store(StoreTuSuratRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';

        $surat = TuSurat::query()->create($data);
        AuditLog::record('tu-surat', 'create', $surat, null, $surat->toArray());

        return redirect()
            ->route('kepegawaian-tu.surat.show', $surat)
            ->with('success', 'Draft surat berhasil dibuat.');
    }

    public function show(TuSurat $tuSurat): View
    {
        $this->authorizeRead($tuSurat);

        $tuSurat->load(['template', 'creator', 'reviewer', 'approver']);

        return view('kepegawaian-tu.surat.show', [
            'tuSurat' => $tuSurat,
            'isPrivilegedUser' => $this->isPrivilegedUser(),
        ]);
    }

    public function edit(TuSurat $tuSurat): View
    {
        abort_if(! $tuSurat->canBeEditedBy(auth()->user()), 403, 'Hanya draft yang dapat diubah.');

        $templates = TuSuratTemplate::query()
            ->where('is_active', true)
            ->orWhereKey($tuSurat->tu_surat_template_id)
            ->orderBy('nama')
            ->get();

        return view('kepegawaian-tu.surat.edit', compact('tuSurat', 'templates'));
    }

    public function update(UpdateTuSuratRequest $request, TuSurat $tuSurat): RedirectResponse
    {
        abort_if(! $tuSurat->canBeEditedBy(auth()->user()), 403, 'Hanya draft yang dapat diubah.');

        $before = $tuSurat->toArray();
        $tuSurat->update($request->validated());

        AuditLog::record('tu-surat', 'update', $tuSurat, $before, $tuSurat->fresh()?->toArray());

        return redirect()
            ->route('kepegawaian-tu.surat.show', $tuSurat)
            ->with('success', 'Draft surat berhasil diperbarui.');
    }

    public function destroy(TuSurat $tuSurat): RedirectResponse
    {
        abort_if(! $tuSurat->canBeEditedBy(auth()->user()), 403, 'Hanya draft yang dapat dihapus.');

        $before = $tuSurat->toArray();
        $tuSurat->delete();

        AuditLog::record('tu-surat', 'delete', null, $before, null);

        return redirect()
            ->route('kepegawaian-tu.surat.index')
            ->with('success', 'Draft surat berhasil dihapus.');
    }

    public function submitReview(TuSurat $tuSurat): RedirectResponse
    {
        abort_if(! $tuSurat->canBeSubmittedBy(auth()->user()), 403, 'Surat ini tidak dapat diajukan review.');

        $before = $tuSurat->toArray();
        $tuSurat->update([
            'status' => 'review',
            'reviewed_by' => auth()->id(),
        ]);

        AuditLog::record('tu-surat', 'submit-review', $tuSurat, $before, $tuSurat->fresh()?->toArray());

        return redirect()
            ->route('kepegawaian-tu.surat.show', $tuSurat)
            ->with('success', 'Surat berhasil diajukan untuk review.');
    }

    public function approveFinal(FinalizeTuSuratRequest $request, TuSurat $tuSurat): RedirectResponse
    {
        abort_if(! $tuSurat->canBeFinalizedBy(auth()->user()), 403, 'Surat harus berstatus review untuk difinalkan.');

        $tanggalSurat = $request->validated('tanggal_surat')
            ? Carbon::parse($request->validated('tanggal_surat'))
            : now();

        $before = $tuSurat->toArray();
        $tuSurat->update([
            'status' => 'final',
            'tanggal_surat' => $tanggalSurat->toDateString(),
            'nomor_surat' => $tuSurat->nomor_surat ?: TuSurat::generateNomorSurat($tanggalSurat),
            'approved_by' => auth()->id(),
            'finalized_at' => now(),
            'verification_token' => $tuSurat->verification_token ?: hash('sha256', Str::uuid()->toString()),
        ]);

        $suratTerkini = $tuSurat->fresh();
        if ($suratTerkini instanceof TuSurat) {
            $suratTerkini->loadMissing('template');
            $this->arsipDokumenService->syncFromSurat($suratTerkini, auth()->id());
        }

        AuditLog::record('tu-surat', 'finalize', $tuSurat, $before, $suratTerkini?->toArray());

        return redirect()
            ->route('kepegawaian-tu.surat.show', $tuSurat)
            ->with('success', 'Surat berhasil difinalkan dan nomor surat telah diterbitkan.');
    }

    public function archive(TuSurat $tuSurat): RedirectResponse
    {
        abort_if(! $tuSurat->canBeArchivedBy(auth()->user()), 403, 'Hanya surat final yang dapat diarsipkan.');

        $before = $tuSurat->toArray();
        $tuSurat->update([
            'status' => 'arsip',
            'archived_at' => now(),
        ]);

        $suratTerkini = $tuSurat->fresh();
        if ($suratTerkini instanceof TuSurat) {
            $suratTerkini->loadMissing('template');
            $this->arsipDokumenService->syncFromSurat($suratTerkini, auth()->id());
        }

        AuditLog::record('tu-surat', 'archive', $tuSurat, $before, $suratTerkini?->toArray());

        return redirect()
            ->route('kepegawaian-tu.surat.show', $tuSurat)
            ->with('success', 'Surat berhasil diarsipkan.');
    }

    public function print(TuSurat $tuSurat): View
    {
        $this->authorizeRead($tuSurat);
        abort_unless(in_array($tuSurat->status, ['final', 'arsip'], true), 403, 'Hanya surat final/arsip yang dapat dicetak.');

        if (! $tuSurat->verification_token) {
            $tuSurat->update([
                'verification_token' => hash('sha256', Str::uuid()->toString()),
            ]);
        }

        $tuSurat->load(['creator', 'approver']);

        $verifyUrl = route('kepegawaian-tu.surat.verify', [
            'tuSurat' => $tuSurat,
            'token' => $tuSurat->verification_token,
        ]);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data='.urlencode($verifyUrl);

        return view('kepegawaian-tu.surat.print', compact('tuSurat', 'verifyUrl', 'qrUrl'));
    }

    public function verify(TuSurat $tuSurat, string $token): View
    {
        abort_unless(
            hash_equals((string) $tuSurat->verification_token, $token)
                && in_array($tuSurat->status, ['final', 'arsip'], true),
            404
        );

        $tuSurat->load(['creator', 'approver']);

        return view('kepegawaian-tu.surat.verify', compact('tuSurat'));
    }

    private function authorizeRead(TuSurat $tuSurat): void
    {
        if ($this->isPrivilegedUser()) {
            return;
        }

        abort_if($tuSurat->created_by !== auth()->id(), 403);
    }

    private function isPrivilegedUser(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'pejabat'], true);
    }
}
