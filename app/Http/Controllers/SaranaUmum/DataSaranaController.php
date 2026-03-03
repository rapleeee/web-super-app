<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaranaUmum\ImportSaranaUmumRequest;
use App\Http\Requests\SaranaUmum\StoreSaranaUmumRequest;
use App\Http\Requests\SaranaUmum\UpdateSaranaUmumRequest;
use App\Models\AuditLog;
use App\Models\SaranaUmum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DataSaranaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $saranaUmums = SaranaUmum::query()
            ->when($request->search, fn ($query, $search) => $query->where(function ($query) use ($search): void {
                $query->where('kode_inventaris', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            }))
            ->when($request->jenis, fn ($query, $jenis) => $query->where('jenis', $jenis))
            ->when($request->kondisi, fn ($query, $kondisi) => $query->where('kondisi', $kondisi))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jenisOptions = SaranaUmum::query()
            ->select('jenis')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis');

        return view('sarana-umum.data-sarana.index', compact('saranaUmums', 'jenisOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('sarana-umum.data-sarana.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaranaUmumRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('sarana-umum', 'public');
        }

        $saranaUmum = SaranaUmum::query()->create($data);

        AuditLog::record('sarana-umum', 'create', $saranaUmum, null, $saranaUmum->toArray());

        return redirect()
            ->route('sarana-umum.data-sarana.index')
            ->with('success', 'Data sarana umum berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaranaUmum $saranaUmum): View
    {
        return view('sarana-umum.data-sarana.show', compact('saranaUmum'));
    }

    /**
     * Display printable QR page for sarana.
     */
    public function qr(SaranaUmum $saranaUmum): View
    {
        $targetUrl = route('sarana-umum.data-sarana.show', $saranaUmum);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($targetUrl);

        return view('sarana-umum.data-sarana.qr', compact('saranaUmum', 'targetUrl', 'qrUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaranaUmum $saranaUmum): View
    {
        return view('sarana-umum.data-sarana.edit', compact('saranaUmum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaranaUmumRequest $request, SaranaUmum $saranaUmum): RedirectResponse
    {
        $before = $saranaUmum->toArray();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($saranaUmum->foto) {
                Storage::disk('public')->delete($saranaUmum->foto);
            }

            $data['foto'] = $request->file('foto')->store('sarana-umum', 'public');
        }

        $saranaUmum->update($data);

        AuditLog::record('sarana-umum', 'update', $saranaUmum, $before, $saranaUmum->fresh()?->toArray());

        return redirect()
            ->route('sarana-umum.data-sarana.index')
            ->with('success', 'Data sarana umum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaranaUmum $saranaUmum): RedirectResponse
    {
        $before = $saranaUmum->toArray();

        if ($saranaUmum->foto) {
            Storage::disk('public')->delete($saranaUmum->foto);
        }

        $saranaUmum->delete();

        AuditLog::record('sarana-umum', 'delete', null, $before, null);

        return redirect()
            ->route('sarana-umum.data-sarana.index')
            ->with('success', 'Data sarana umum berhasil dihapus.');
    }

    public function importForm(): View
    {
        return view('sarana-umum.data-sarana.import');
    }

    public function importPreview(ImportSaranaUmumRequest $request): View|RedirectResponse
    {
        $file = $request->file('file');
        if (! $file) {
            return redirect()->route('sarana-umum.data-sarana.import')->with('error', 'File CSV tidak ditemukan.');
        }

        $rawRows = file($file->getRealPath());
        if ($rawRows === false) {
            return redirect()->route('sarana-umum.data-sarana.import')->with('error', 'Gagal membaca file CSV.');
        }

        $rows = array_map('str_getcsv', $rawRows);
        if (count($rows) < 2) {
            return redirect()->route('sarana-umum.data-sarana.import')->with('error', 'File CSV tidak memiliki data.');
        }

        $header = array_map(fn ($item) => Str::lower(trim((string) $item)), $rows[0]);
        $required = ['kode_inventaris', 'nama', 'jenis', 'lokasi', 'kondisi', 'status'];

        foreach ($required as $column) {
            if (! in_array($column, $header, true)) {
                return redirect()
                    ->route('sarana-umum.data-sarana.import')
                    ->with('error', "Kolom {$column} tidak ditemukan di CSV.");
            }
        }

        $mappedRows = [];
        foreach (array_slice($rows, 1) as $index => $row) {
            $normalizedRow = array_slice(array_pad($row, count($header), null), 0, count($header));
            $line = array_combine($header, $normalizedRow);
            if (! $line) {
                continue;
            }

            $mappedRows[] = [
                'row' => $index + 2,
                'kode_inventaris' => trim((string) ($line['kode_inventaris'] ?? '')),
                'nama' => trim((string) ($line['nama'] ?? '')),
                'jenis' => trim((string) ($line['jenis'] ?? '')),
                'lokasi' => trim((string) ($line['lokasi'] ?? '')),
                'merk' => trim((string) ($line['merk'] ?? '')) ?: null,
                'model' => trim((string) ($line['model'] ?? '')) ?: null,
                'nomor_seri' => trim((string) ($line['nomor_seri'] ?? '')) ?: null,
                'tahun_pengadaan' => is_numeric($line['tahun_pengadaan'] ?? null) ? (int) $line['tahun_pengadaan'] : null,
                'kondisi' => trim((string) ($line['kondisi'] ?? 'baik')),
                'status' => trim((string) ($line['status'] ?? 'aktif')),
                'keterangan' => trim((string) ($line['keterangan'] ?? '')) ?: null,
            ];
        }

        session(['sarana_umum.import_rows' => $mappedRows]);

        return view('sarana-umum.data-sarana.import-preview', [
            'previewRows' => collect($mappedRows)->take(50),
            'totalRows' => count($mappedRows),
        ]);
    }

    public function importProcess(Request $request): RedirectResponse
    {
        $rows = session('sarana_umum.import_rows', []);
        if (empty($rows)) {
            return redirect()->route('sarana-umum.data-sarana.import')->with('error', 'Data preview import tidak ditemukan.');
        }

        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            if (empty($row['kode_inventaris']) || empty($row['nama']) || empty($row['jenis']) || empty($row['lokasi'])) {
                continue;
            }

            $data = [
                'nama' => $row['nama'],
                'jenis' => $row['jenis'],
                'lokasi' => $row['lokasi'],
                'merk' => $row['merk'],
                'model' => $row['model'],
                'nomor_seri' => $row['nomor_seri'],
                'tahun_pengadaan' => $row['tahun_pengadaan'],
                'kondisi' => in_array($row['kondisi'], ['baik', 'rusak_ringan', 'rusak_berat', 'mati_total'], true) ? $row['kondisi'] : 'baik',
                'status' => in_array($row['status'], ['aktif', 'dalam_perbaikan', 'tidak_aktif'], true) ? $row['status'] : 'aktif',
                'keterangan' => $row['keterangan'],
            ];

            $record = SaranaUmum::query()->where('kode_inventaris', $row['kode_inventaris'])->first();

            if ($record) {
                $record->update($data);
                $updated++;

                continue;
            }

            SaranaUmum::query()->create([
                'kode_inventaris' => $row['kode_inventaris'],
                ...$data,
            ]);
            $created++;
        }

        session()->forget('sarana_umum.import_rows');

        AuditLog::record('sarana-umum', 'import', null, null, [
            'created' => $created,
            'updated' => $updated,
            'total' => count($rows),
        ]);

        return redirect()
            ->route('sarana-umum.data-sarana.index')
            ->with('success', "Import selesai. {$created} data baru, {$updated} data diperbarui.");
    }

    public function downloadTemplate(): Response
    {
        $columns = [
            'kode_inventaris',
            'nama',
            'jenis',
            'lokasi',
            'merk',
            'model',
            'nomor_seri',
            'tahun_pengadaan',
            'kondisi',
            'status',
            'keterangan',
        ];

        $sample = [
            'SRN-001',
            'Proyektor Aula',
            'Proyektor',
            'Aula Utama',
            'Epson',
            'EB-X06',
            'SN-12345',
            '2024',
            'baik',
            'aktif',
            'Terpasang permanen',
        ];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $columns);
        fputcsv($output, $sample);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template-sarana-umum.csv"');
    }
}
