<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaranaUmum extends Model
{
    /** @use HasFactory<\Database\Factories\SaranaUmumFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
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
        'foto',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun_pengadaan' => 'integer',
        ];
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(SaranaUmumMaintenanceLog::class);
    }

    public function preventiveMaintenances(): HasMany
    {
        return $this->hasMany(SaranaUmumPreventiveMaintenance::class);
    }

    public function beritaAcaras(): HasMany
    {
        return $this->hasMany(SaranaUmumBeritaAcara::class);
    }
}
