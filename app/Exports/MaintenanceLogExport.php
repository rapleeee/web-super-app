<?php

namespace App\Exports;

use App\Models\MaintenanceLog;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceLogExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\MaintenanceLog>  $maintenanceLogs
     */
    public function __construct(
        private readonly Collection $maintenanceLogs
    ) {}

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\MaintenanceLog>
     */
    public function collection(): Collection
    {
        return $this->maintenanceLogs;
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Lapor',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Laboratorium',
            'Unit Komputer',
            'Komponen',
            'Kode Inventaris',
            'Pelapor',
            'Keluhan',
            'Diagnosa',
            'Tindakan',
            'Teknisi',
            'Biaya',
            'Kondisi Sebelum',
            'Kondisi Sesudah',
            'Status',
            'Durasi (Hari)',
            'Catatan',
        ];
    }

    /**
     * @return list<string|int|float>
     */
    public function map($row): array
    {
        /** @var MaintenanceLog $row */
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $this->formatDate($row->tanggal_lapor),
            $this->formatDate($row->tanggal_mulai),
            $this->formatDate($row->tanggal_selesai),
            $row->komponenPerangkat?->unitKomputer?->laboratorium?->nama ?? '-',
            $row->komponenPerangkat?->unitKomputer?->nama ?? '-',
            $row->komponenPerangkat?->kategori?->nama ?? '-',
            $row->komponenPerangkat?->kode_inventaris ?? '-',
            $row->pelapor?->name ?? '-',
            $row->keluhan ?? '-',
            $row->diagnosa ?? '-',
            $row->tindakan ?? '-',
            $row->teknisi ?? '-',
            (float) ($row->biaya ?? 0),
            $row->kondisi_sebelum ? ucfirst(str_replace('_', ' ', $row->kondisi_sebelum)) : '-',
            $row->kondisi_sesudah ? ucfirst(str_replace('_', ' ', $row->kondisi_sesudah)) : '-',
            ucfirst(str_replace('_', ' ', $row->status)),
            $row->durasi ?? '-',
            $row->catatan ?? '-',
        ];
    }

    private function formatDate(?CarbonInterface $date): string
    {
        return $date?->format('d/m/Y') ?? '-';
    }
}
