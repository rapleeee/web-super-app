<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaranaUmum\StoreSaranaUmumBeritaAcaraRequest;
use App\Models\AuditLog;
use App\Models\Guru;
use App\Models\Laboratorium;
use App\Models\SaranaUmum;
use App\Models\SaranaUmumBeritaAcara;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BeritaAcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $beritaAcaras = SaranaUmumBeritaAcara::query()
            ->with(['saranaUmum', 'ruangan', 'user'])
            ->latest('tanggal')
            ->paginate(15);

        return view('sarana-umum.berita-acara.index', compact('beritaAcaras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $saranaUmums = SaranaUmum::query()
            ->where('status', '!=', 'tidak_aktif')
            ->orderBy('nama')
            ->get();

        $ruangans = Laboratorium::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $gurus = Guru::aktif()->orderBy('nama')->get();

        return view('sarana-umum.berita-acara.create', compact('saranaUmums', 'ruangans', 'gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaranaUmumBeritaAcaraRequest $request): RedirectResponse
    {
        $beritaAcara = SaranaUmumBeritaAcara::query()->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);
        AuditLog::record('berita-acara', 'create', $beritaAcara, null, $beritaAcara->toArray());

        return redirect()
            ->route('sarana-umum.berita-acara.index')
            ->with('success', 'Berita acara sarana umum berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaranaUmumBeritaAcara $beritaAcara): View
    {
        $beritaAcara->load(['saranaUmum', 'ruangan', 'user']);

        return view('sarana-umum.berita-acara.show', compact('beritaAcara'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaranaUmumBeritaAcara $beritaAcara): View
    {
        $saranaUmums = SaranaUmum::query()
            ->where('status', '!=', 'tidak_aktif')
            ->orderBy('nama')
            ->get();

        $ruangans = Laboratorium::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $gurus = Guru::aktif()->orderBy('nama')->get();

        return view('sarana-umum.berita-acara.edit', compact('beritaAcara', 'saranaUmums', 'ruangans', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSaranaUmumBeritaAcaraRequest $request, SaranaUmumBeritaAcara $beritaAcara): RedirectResponse
    {
        $before = $beritaAcara->toArray();
        $beritaAcara->update($request->validated());
        AuditLog::record('berita-acara', 'update', $beritaAcara, $before, $beritaAcara->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.berita-acara.show', $beritaAcara)
            ->with('success', 'Berita acara sarana umum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaranaUmumBeritaAcara $beritaAcara): RedirectResponse
    {
        $before = $beritaAcara->toArray();
        $beritaAcara->delete();
        AuditLog::record('berita-acara', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.berita-acara.index')
            ->with('success', 'Berita acara sarana umum berhasil dihapus.');
    }

    /**
     * Export berita acara as CSV report.
     */
    public function export(Request $request): Response
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : now()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : now()->endOfMonth();

        $saranaUmumId = $request->input('sarana_umum_id');

        $query = SaranaUmumBeritaAcara::query()
            ->with(['saranaUmum', 'ruangan'])
            ->whereBetween('tanggal', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->where('status', 'final')
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai');

        if ($saranaUmumId) {
            $query->where('sarana_umum_id', $saranaUmumId);
        }

        $beritaAcaras = $query->get();

        $csvContent = [];
        $csvContent[] = ['LAPORAN BERITA ACARA SARANA UMUM'];
        $csvContent[] = ['Periode: '.$startDate->format('d M Y').' - '.$endDate->format('d M Y')];

        if ($saranaUmumId) {
            $sarana = SaranaUmum::query()->find($saranaUmumId);
            $csvContent[] = ['Sarana Umum: '.($sarana?->nama ?? '-')];
        }

        $csvContent[] = ['Tanggal Cetak: '.now()->format('d M Y H:i')];
        $csvContent[] = [];

        $csvContent[] = ['=== RINGKASAN ==='];
        $csvContent[] = [];
        $csvContent[] = ['Total Sesi Penggunaan', $beritaAcaras->count()];
        $csvContent[] = ['Total Peserta (Akumulatif)', $beritaAcaras->sum('jumlah_peserta')];
        $csvContent[] = [];

        $csvContent[] = ['=== DETAIL BERITA ACARA ==='];
        $csvContent[] = [];
        $csvContent[] = [
            'No',
            'Tanggal',
            'Waktu',
            'Sarana',
            'Ruangan',
            'Nama Guru',
            'Mata Pelajaran',
            'Kelas',
            'Jumlah Peserta',
            'Kegiatan',
            'Catatan',
        ];

        foreach ($beritaAcaras as $index => $ba) {
            $csvContent[] = [
                $index + 1,
                $ba->tanggal->format('d/m/Y'),
                Carbon::parse($ba->waktu_mulai)->format('H:i').'-'.Carbon::parse($ba->waktu_selesai)->format('H:i'),
                $ba->saranaUmum?->nama ?? '-',
                $ba->ruangan?->nama ?? '-',
                $ba->nama_guru,
                $ba->mata_pelajaran ?? '-',
                $ba->kelas,
                $ba->jumlah_peserta,
                $ba->kegiatan ?? '-',
                $ba->catatan ?? '-',
            ];
        }

        $output = fopen('php://temp', 'r+');
        foreach ($csvContent as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        $filename = 'berita-acara-sarana-umum-'.$startDate->format('Ymd').'-'.$endDate->format('Ymd').'.csv';
        AuditLog::record('berita-acara', 'export', null, null, [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'sarana_umum_id' => $saranaUmumId,
            'exported_rows' => $beritaAcaras->count(),
        ]);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
