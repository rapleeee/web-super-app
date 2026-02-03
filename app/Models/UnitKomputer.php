<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKomputer extends Model
{
    /** @use HasFactory<\Database\Factories\UnitKomputerFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'kode_unit',
        'nama',
        'laboratorium_id',
        'nomor_meja',
        'kondisi',
        'status',
        'keterangan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nomor_meja' => 'integer',
        ];
    }

    /**
     * Get the laboratorium that owns this unit.
     */
    public function laboratorium(): BelongsTo
    {
        return $this->belongsTo(Laboratorium::class);
    }

    /**
     * Get the komponen perangkat for this unit.
     */
    public function komponenPerangkats(): HasMany
    {
        return $this->hasMany(KomponenPerangkat::class);
    }

    /**
     * Get count of komponen by kondisi.
     *
     * @return array<string, int>
     */
    public function getKondisiSummaryAttribute(): array
    {
        return [
            'baik' => $this->komponenPerangkats()->where('kondisi', 'baik')->count(),
            'rusak_ringan' => $this->komponenPerangkats()->where('kondisi', 'rusak_ringan')->count(),
            'rusak_berat' => $this->komponenPerangkats()->where('kondisi', 'rusak_berat')->count(),
            'mati_total' => $this->komponenPerangkats()->where('kondisi', 'mati_total')->count(),
        ];
    }
}
