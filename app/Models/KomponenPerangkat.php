<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KomponenPerangkat extends Model
{
    /** @use HasFactory<\Database\Factories\KomponenPerangkatFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'kode_inventaris',
        'unit_komputer_id',
        'kategori_id',
        'merk',
        'model',
        'nomor_seri',
        'tahun_pengadaan',
        'kondisi',
        'status',
        'spesifikasi',
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

    /**
     * Get the unit komputer that owns this komponen.
     */
    public function unitKomputer(): BelongsTo
    {
        return $this->belongsTo(UnitKomputer::class);
    }

    /**
     * Get the kategori of this komponen.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPerangkat::class, 'kategori_id');
    }

    /**
     * Get the maintenance logs for this komponen.
     */
    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    /**
     * Get latest maintenance log.
     */
    public function latestMaintenance(): ?MaintenanceLog
    {
        return $this->maintenanceLogs()->latest('tanggal_lapor')->first();
    }
}
