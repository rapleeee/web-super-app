<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\BulkDeleteUnitKomputerRequest;
use App\Http\Requests\Laboran\BulkUpdateUnitKomputerRequest;
use App\Http\Requests\Laboran\StoreUnitKomputerRequest;
use App\Http\Requests\Laboran\UpdateUnitKomputerRequest;
use App\Imports\UnitKomputerImport;
use App\Models\Laboratorium;
use App\Models\UnitKomputer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UnitKomputerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $perPageOptions = [5, 10, 30, 50, 100];
        $perPage = (int) $request->integer('per_page', 10);
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $unitKomputers = UnitKomputer::query()
            ->with('laboratorium')
            ->withCount('komponenPerangkats')
            ->when($request->laboratorium, fn ($q, $id) => $q->where('laboratorium_id', $id))
            ->when($request->kondisi, fn ($q, $kondisi) => $q->where('kondisi', $kondisi))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_unit', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $laboratoriums = Laboratorium::where('status', 'aktif')->get();

        return view('laboran.perangkat.unit.index', compact('unitKomputers', 'laboratoriums', 'perPageOptions', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $laboratoriums = Laboratorium::where('status', 'aktif')->get();

        return view('laboran.perangkat.unit.create', compact('laboratoriums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitKomputerRequest $request): RedirectResponse
    {
        UnitKomputer::create($request->validated());

        return redirect()
            ->route('laboran.unit-komputer.index')
            ->with('success', 'Unit komputer berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitKomputer $unitKomputer): View
    {
        $unitKomputer->load([
            'laboratorium',
            'komponenPerangkats.kategori',
            'komponenPerangkats.maintenanceLogs' => fn ($q) => $q->latest('tanggal_lapor')->limit(1),
        ]);

        return view('laboran.perangkat.unit.show', compact('unitKomputer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitKomputer $unitKomputer): View
    {
        $laboratoriums = Laboratorium::where('status', 'aktif')->get();

        return view('laboran.perangkat.unit.edit', compact('unitKomputer', 'laboratoriums'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitKomputerRequest $request, UnitKomputer $unitKomputer): RedirectResponse
    {
        $unitKomputer->update($request->validated());

        return redirect()
            ->route('laboran.unit-komputer.index')
            ->with('success', 'Unit komputer berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitKomputer $unitKomputer): RedirectResponse
    {
        $unitKomputer->delete();

        return redirect()
            ->route('laboran.unit-komputer.index')
            ->with('success', 'Unit komputer berhasil dihapus.');
    }

    /**
     * Remove selected resources from storage.
     */
    public function bulkDelete(BulkDeleteUnitKomputerRequest $request): RedirectResponse
    {
        $deletedCount = UnitKomputer::query()
            ->whereIn('id', $request->validated('unit_ids'))
            ->delete();

        return back()->with('success', "{$deletedCount} unit komputer berhasil dihapus.");
    }

    /**
     * Update selected resources in storage.
     */
    public function bulkUpdate(BulkUpdateUnitKomputerRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $unitIds = $validated['unit_ids'];
        $mode = $validated['mode'];

        $payload = collect($validated)
            ->only(['laboratorium_id', 'nomor_meja', 'kondisi', 'status', 'keterangan'])
            ->reject(fn (mixed $value): bool => is_null($value) || $value === '')
            ->all();

        $updatedCount = 0;

        if ($mode === 'overwrite') {
            $updatedCount = UnitKomputer::query()
                ->whereIn('id', $unitIds)
                ->update($payload);
        } else {
            $unitKomputers = UnitKomputer::query()
                ->whereIn('id', $unitIds)
                ->get();

            foreach ($unitKomputers as $unitKomputer) {
                $fieldsToUpdate = [];

                foreach ($payload as $field => $value) {
                    if ($this->isFieldEmpty($unitKomputer->{$field})) {
                        $fieldsToUpdate[$field] = $value;
                    }
                }

                if ($fieldsToUpdate !== []) {
                    $unitKomputer->update($fieldsToUpdate);
                    $updatedCount++;
                }
            }
        }

        $message = $mode === 'overwrite'
            ? "{$updatedCount} unit komputer berhasil diperbarui."
            : "{$updatedCount} unit komputer berhasil diisi field kosongnya.";

        return back()->with('success', $message);
    }

    /**
     * Download template import.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $templateFilename = 'template_unit_komputer.csv';
        $templatePath = 'templates/'.$templateFilename;
        $disk = Storage::disk('local');

        if ($disk->exists($templatePath)) {
            return $disk->download($templatePath, $templateFilename, [
                'Content-Type' => 'text/csv',
            ]);
        }

        $templateRows = [
            ['kode_unit', 'nama', 'laboratorium', 'kondisi', 'status'],
            ['PC-001', 'Komputer 1', 'Lab Komputer A', 'baik', 'aktif'],
        ];

        return response()->streamDownload(function () use ($templateRows): void {
            $output = fopen('php://output', 'w');

            foreach ($templateRows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $templateFilename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Show import form.
     */
    public function importForm(): View
    {
        return view('laboran.perangkat.unit.import');
    }

    /**
     * Preview import data.
     */
    public function importPreview(Request $request): View|RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:2048'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes' => 'File harus berformat CSV, XLSX, atau XLS.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $disk = 'local';
            $path = $request->file('file')->store('imports/temp', $disk);

            $data = Excel::toArray([], $request->file('file'))[0];

            if (count($data) < 2) {
                return back()->with('error', 'File kosong atau hanya berisi header.');
            }

            $headers = array_map('strtolower', array_map('trim', $data[0]));
            $rows = array_slice($data, 1);

            $previewData = [];
            $laboratoriums = Laboratorium::pluck('id', 'nama')->toArray();
            $existingCodes = UnitKomputer::pluck('kode_unit')->toArray();

            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                $rowData = array_combine($headers, $row);
                $errors = [];

                if (empty($rowData['kode_unit'])) {
                    $errors[] = 'Kode unit wajib diisi';
                } elseif (in_array($rowData['kode_unit'], $existingCodes)) {
                    $errors[] = 'Kode unit sudah ada';
                }

                if (empty($rowData['nama'])) {
                    $errors[] = 'Nama wajib diisi';
                }

                if (empty($rowData['laboratorium'])) {
                    $errors[] = 'Laboratorium wajib diisi';
                } elseif (! isset($laboratoriums[$rowData['laboratorium']])) {
                    $errors[] = 'Laboratorium tidak ditemukan';
                }

                $kondisiValid = ['baik', 'rusak_ringan', 'rusak_berat', 'mati_total', ''];
                if (! empty($rowData['kondisi']) && ! in_array($rowData['kondisi'], $kondisiValid)) {
                    $errors[] = 'Kondisi tidak valid';
                }

                $statusValid = ['aktif', 'dalam_perbaikan', 'tidak_aktif', ''];
                if (! empty($rowData['status']) && ! in_array($rowData['status'], $statusValid)) {
                    $errors[] = 'Status tidak valid';
                }

                $previewData[] = [
                    'row_number' => $index + 2,
                    'data' => $rowData,
                    'errors' => $errors,
                    'valid' => empty($errors),
                ];
            }

            session([
                'unit_komputer.import_file' => [
                    'disk' => $disk,
                    'path' => $path,
                ],
                'import_file_path' => $path,
            ]);

            $validCount = count(array_filter($previewData, fn ($row) => $row['valid']));
            $invalidCount = count($previewData) - $validCount;

            return view('laboran.perangkat.unit.import-preview', compact('previewData', 'validCount', 'invalidCount'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file: '.$e->getMessage());
        }
    }

    /**
     * Process confirmed import.
     */
    public function importProcess(Request $request): RedirectResponse
    {
        $importFile = session('unit_komputer.import_file');
        $diskName = 'local';
        $path = null;

        if (is_array($importFile)) {
            $diskName = $importFile['disk'] ?? 'local';
            $path = $importFile['path'] ?? null;
        } elseif (is_string(session('import_file_path'))) {
            $path = session('import_file_path');

            $defaultDisk = config('filesystems.default', 'local');
            $defaultDiskDriver = config("filesystems.disks.{$defaultDisk}.driver");

            if ($defaultDiskDriver === 'local' && Storage::disk($defaultDisk)->exists($path)) {
                $diskName = $defaultDisk;
            }
        }

        $disk = Storage::disk($diskName);

        if (! $path || ! $disk->exists($path)) {
            return redirect()
                ->route('laboran.unit-komputer.import')
                ->with('error', 'File import tidak ditemukan. Silakan upload ulang.');
        }

        try {
            $import = new UnitKomputerImport;
            Excel::import($import, $disk->path($path));

            $disk->delete($path);
            session()->forget(['unit_komputer.import_file', 'import_file_path']);

            $failures = $import->failures();

            if ($failures->isNotEmpty()) {
                $errors = $failures->map(fn ($failure) => "Baris {$failure->row()}: ".implode(', ', $failure->errors()))->toArray();

                return redirect()
                    ->route('laboran.unit-komputer.index')
                    ->with('warning', 'Import selesai dengan beberapa error.')
                    ->with('import_errors', $errors);
            }

            return redirect()
                ->route('laboran.unit-komputer.index')
                ->with('success', 'Data unit komputer berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()
                ->route('laboran.unit-komputer.import')
                ->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    private function isFieldEmpty(mixed $value): bool
    {
        if (is_string($value)) {
            return trim($value) === '';
        }

        return is_null($value);
    }
}
