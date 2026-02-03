<?php

namespace App\Imports;

use App\Models\Laboratorium;
use App\Models\UnitKomputer;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UnitKomputerImport implements SkipsEmptyRows, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    use SkipsFailures;

    private array $laboratoriums = [];

    public function __construct()
    {
        // Cache laboratorium untuk lookup
        $this->laboratoriums = Laboratorium::pluck('id', 'nama')->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cari laboratorium_id dari nama
        $laboratoriumId = $this->laboratoriums[$row['laboratorium']] ?? null;

        if (! $laboratoriumId) {
            return null;
        }

        return new UnitKomputer([
            'kode_unit' => $row['kode_unit'],
            'nama' => $row['nama'],
            'laboratorium_id' => $laboratoriumId,
            'nomor_meja' => $row['nomor_meja'] ?? null,
            'kondisi' => $row['kondisi'] ?? 'baik',
            'status' => $row['status'] ?? 'aktif',
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_unit' => ['required', 'string', 'max:50', 'unique:unit_komputers,kode_unit'],
            'nama' => ['required', 'string', 'max:100'],
            'laboratorium' => ['required', 'string', 'exists:laboratoria,nama'],
            'nomor_meja' => ['nullable', 'integer', 'min:1'],
            'kondisi' => ['nullable', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'status' => ['nullable', 'in:aktif,dalam_perbaikan,tidak_aktif'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'kode_unit.required' => 'Kolom kode_unit wajib diisi pada baris :attribute.',
            'kode_unit.unique' => 'Kode unit sudah ada pada baris :attribute.',
            'nama.required' => 'Kolom nama wajib diisi pada baris :attribute.',
            'laboratorium.required' => 'Kolom laboratorium wajib diisi pada baris :attribute.',
            'laboratorium.exists' => 'Laboratorium tidak ditemukan pada baris :attribute.',
            'kondisi.in' => 'Kondisi tidak valid pada baris :attribute.',
            'status.in' => 'Status tidak valid pada baris :attribute.',
        ];
    }
}
