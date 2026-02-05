<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\StoreBeritaAcaraRequest;
use App\Models\BeritaAcara;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Laboratorium;
use App\Models\MataPelajaran;
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
        $beritaAcaras = BeritaAcara::query()
            ->with(['laboratorium', 'user'])
            ->latest('tanggal')
            ->paginate(15);

        return view('laboran.berita-acara.index', compact('beritaAcaras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $laboratoriums = Laboratorium::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $gurus = Guru::aktif()->orderBy('nama')->get();
        $mataPelajarans = MataPelajaran::aktif()->orderBy('nama')->get();
        $kelass = Kelas::aktif()->orderBy('tingkat')->orderBy('jurusan')->orderBy('rombel')->get();
        $alatTambahanOptions = BeritaAcara::alatTambahanOptions();

        return view('laboran.berita-acara.create', compact(
            'laboratoriums',
            'gurus',
            'mataPelajarans',
            'kelass',
            'alatTambahanOptions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeritaAcaraRequest $request): RedirectResponse
    {
        BeritaAcara::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('laboran.berita-acara.index')
            ->with('success', 'Berita Acara berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BeritaAcara $beritaAcara): View
    {
        $beritaAcara->load(['laboratorium', 'user']);

        return view('laboran.berita-acara.show', compact('beritaAcara'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeritaAcara $beritaAcara): View
    {
        $laboratoriums = Laboratorium::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $gurus = Guru::aktif()->orderBy('nama')->get();
        $mataPelajarans = MataPelajaran::aktif()->orderBy('nama')->get();
        $kelass = Kelas::aktif()->orderBy('tingkat')->orderBy('jurusan')->orderBy('rombel')->get();
        $alatTambahanOptions = BeritaAcara::alatTambahanOptions();

        return view('laboran.berita-acara.edit', compact(
            'beritaAcara',
            'laboratoriums',
            'gurus',
            'mataPelajarans',
            'kelass',
            'alatTambahanOptions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBeritaAcaraRequest $request, BeritaAcara $beritaAcara): RedirectResponse
    {
        $beritaAcara->update($request->validated());

        return redirect()
            ->route('laboran.berita-acara.show', $beritaAcara)
            ->with('success', 'Berita Acara berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeritaAcara $beritaAcara): RedirectResponse
    {
        $beritaAcara->delete();

        return redirect()
            ->route('laboran.berita-acara.index')
            ->with('success', 'Berita Acara berhasil dihapus.');
    }

    /**
     * Export berita acara as CSV report.
     */
    public function export(Request $request): Response
    {
        // Date range filter
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : now()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : now()->endOfMonth();

        // Optional lab filter
        $labId = $request->input('laboratorium_id');

        // Query berita acara
        $query = BeritaAcara::query()
            ->with(['laboratorium', 'user'])
            ->whereBetween('tanggal', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->where('status', 'final')
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai');

        if ($labId) {
            $query->where('laboratorium_id', $labId);
        }

        $beritaAcaras = $query->get();

        // Build CSV content
        $csvContent = [];

        // Header info
        $csvContent[] = ['LAPORAN BERITA ACARA PENGGUNAAN LABORATORIUM'];
        $csvContent[] = ['Periode: '.$startDate->format('d M Y').' - '.$endDate->format('d M Y')];
        if ($labId) {
            $lab = Laboratorium::find($labId);
            $csvContent[] = ['Laboratorium: '.($lab ? $lab->nama : '-')];
        }
        $csvContent[] = ['Tanggal Cetak: '.now()->format('d M Y H:i')];
        $csvContent[] = [];

        // Summary
        $csvContent[] = ['=== RINGKASAN ==='];
        $csvContent[] = [];

        $totalSiswa = $beritaAcaras->sum('jumlah_siswa');
        $totalPc = $beritaAcaras->sum('jumlah_pc_digunakan');
        $totalSesi = $beritaAcaras->count();

        // Calculate total hours
        $totalMinutes = $beritaAcaras->reduce(function ($carry, $ba) {
            $start = Carbon::parse($ba->waktu_mulai);
            $end = Carbon::parse($ba->waktu_selesai);

            return $carry + $start->diffInMinutes($end);
        }, 0);
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        // Equipment usage count
        $alatUsage = [];
        foreach ($beritaAcaras as $ba) {
            if ($ba->alat_tambahan) {
                foreach ($ba->alat_tambahan as $alat) {
                    $alatUsage[$alat] = ($alatUsage[$alat] ?? 0) + 1;
                }
            }
        }

        $csvContent[] = ['Total Sesi Penggunaan', $totalSesi];
        $csvContent[] = ['Total Siswa (Akumulatif)', $totalSiswa];
        $csvContent[] = ['Total PC Digunakan (Akumulatif)', $totalPc];
        $csvContent[] = ['Total Jam Penggunaan', $totalHours.' jam '.$remainingMinutes.' menit'];
        $csvContent[] = [];

        if (! empty($alatUsage)) {
            $csvContent[] = ['Penggunaan Alat Tambahan:'];
            foreach ($alatUsage as $alat => $count) {
                $csvContent[] = ['  - '.$alat, $count.' kali'];
            }
            $csvContent[] = [];
        }

        // Per Lab Summary
        $csvContent[] = ['=== REKAP PER LABORATORIUM ==='];
        $csvContent[] = [];

        $perLabStats = $beritaAcaras->groupBy('laboratorium_id');
        foreach ($perLabStats as $labId => $labItems) {
            $labName = $labItems->first()->laboratorium->nama ?? 'Unknown';
            $csvContent[] = [$labName];
            $csvContent[] = ['  Jumlah Sesi', $labItems->count()];
            $csvContent[] = ['  Total Siswa', $labItems->sum('jumlah_siswa')];
            $csvContent[] = ['  Total PC', $labItems->sum('jumlah_pc_digunakan')];
            $csvContent[] = [];
        }

        // Detail Table
        $csvContent[] = ['=== DETAIL BERITA ACARA ==='];
        $csvContent[] = [];
        $csvContent[] = [
            'No',
            'Tanggal',
            'Waktu',
            'Laboratorium',
            'Nama Guru',
            'Mata Pelajaran',
            'Kelas',
            'Jml Siswa',
            'Jml PC',
            'Alat Tambahan',
            'Kegiatan',
            'Catatan',
        ];

        $no = 1;
        foreach ($beritaAcaras as $ba) {
            $csvContent[] = [
                $no++,
                $ba->tanggal->format('d/m/Y'),
                Carbon::parse($ba->waktu_mulai)->format('H:i').'-'.Carbon::parse($ba->waktu_selesai)->format('H:i'),
                $ba->laboratorium->nama,
                $ba->nama_guru,
                $ba->mata_pelajaran ?? '-',
                $ba->kelas,
                $ba->jumlah_siswa,
                $ba->jumlah_pc_digunakan,
                $ba->alat_tambahan ? implode(', ', $ba->alat_tambahan) : '-',
                $ba->kegiatan ?? '-',
                $ba->catatan ?? '-',
            ];
        }

        // Generate CSV
        $output = fopen('php://temp', 'r+');
        foreach ($csvContent as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        $filename = 'berita-acara-'.$startDate->format('Ymd').'-'.$endDate->format('Ymd').'.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
