<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaranaUmumMaintenanceLog extends Model
{
    /** @use HasFactory<\Database\Factories\SaranaUmumMaintenanceLogFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sarana_umum_id',
        'pelapor_id',
        'tanggal_lapor',
        'tanggal_mulai',
        'tanggal_selesai',
        'sla_deadline',
        'reminder_sent_at',
        'keluhan',
        'diagnosa',
        'tindakan',
        'teknisi',
        'biaya',
        'status',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'catatan',
        'bukti_sebelum',
        'bukti_sesudah',
        'bukti_invoice',
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
            'sla_deadline' => 'date',
            'reminder_sent_at' => 'datetime',
            'biaya' => 'decimal:2',
        ];
    }

    public function saranaUmum(): BelongsTo
    {
        return $this->belongsTo(SaranaUmum::class);
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function getDurasiAttribute(): ?int
    {
        if ($this->tanggal_selesai && $this->tanggal_lapor) {
            return $this->tanggal_lapor->diffInDays($this->tanggal_selesai);
        }

        return null;
    }

    public function getIsSlaBreachedAttribute(): bool
    {
        if (! $this->sla_deadline || in_array($this->status, ['selesai', 'tidak_bisa_diperbaiki'], true)) {
            return false;
        }

        return now()->isAfter($this->sla_deadline);
    }
}
