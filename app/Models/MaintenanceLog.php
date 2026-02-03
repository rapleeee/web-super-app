<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceLogFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'komponen_perangkat_id',
        'pelapor_id',
        'tanggal_lapor',
        'tanggal_mulai',
        'tanggal_selesai',
        'keluhan',
        'diagnosa',
        'tindakan',
        'teknisi',
        'biaya',
        'status',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'catatan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lapor' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    /**
     * Get the komponen perangkat for this log.
     */
    public function komponenPerangkat(): BelongsTo
    {
        return $this->belongsTo(KomponenPerangkat::class);
    }

    /**
     * Get the pelapor (user) for this log.
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    /**
     * Get duration in days.
     */
    public function getDurasiAttribute(): ?int
    {
        if ($this->tanggal_selesai && $this->tanggal_lapor) {
            return $this->tanggal_lapor->diffInDays($this->tanggal_selesai);
        }

        return null;
    }
}
