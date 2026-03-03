<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepegawaianTu\StoreTuSuratTemplateRequest;
use App\Http\Requests\KepegawaianTu\UpdateTuSuratTemplateRequest;
use App\Models\AuditLog;
use App\Models\TuSuratTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $templates = TuSuratTemplate::query()
            ->when($request->search, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%");
                });
            })
            ->when($request->status !== null && $request->status !== '', function ($query) use ($request): void {
                $query->where('is_active', $request->boolean('status'));
            })
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('kepegawaian-tu.surat-template.index', compact('templates'));
    }

    public function create(): View
    {
        return view('kepegawaian-tu.surat-template.create');
    }

    public function store(StoreTuSuratTemplateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $template = TuSuratTemplate::query()->create($data);
        AuditLog::record('tu-surat-template', 'create', $template, null, $template->toArray());

        return redirect()
            ->route('kepegawaian-tu.template-surat.index')
            ->with('success', 'Template surat berhasil dibuat.');
    }

    public function edit(TuSuratTemplate $tuSuratTemplate): View
    {
        return view('kepegawaian-tu.surat-template.edit', compact('tuSuratTemplate'));
    }

    public function update(UpdateTuSuratTemplateRequest $request, TuSuratTemplate $tuSuratTemplate): RedirectResponse
    {
        $before = $tuSuratTemplate->toArray();

        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $tuSuratTemplate->update($data);

        AuditLog::record('tu-surat-template', 'update', $tuSuratTemplate, $before, $tuSuratTemplate->fresh()?->toArray());

        return redirect()
            ->route('kepegawaian-tu.template-surat.index')
            ->with('success', 'Template surat berhasil diperbarui.');
    }

    public function destroy(TuSuratTemplate $tuSuratTemplate): RedirectResponse
    {
        if ($tuSuratTemplate->surats()->exists()) {
            return redirect()
                ->route('kepegawaian-tu.template-surat.index')
                ->with('warning', 'Template tidak dapat dihapus karena sudah dipakai pada data surat.');
        }

        $before = $tuSuratTemplate->toArray();
        $tuSuratTemplate->delete();

        AuditLog::record('tu-surat-template', 'delete', null, $before, null);

        return redirect()
            ->route('kepegawaian-tu.template-surat.index')
            ->with('success', 'Template surat berhasil dihapus.');
    }
}
