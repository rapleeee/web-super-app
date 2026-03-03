<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaranaUmumPreventiveMaintenance extends Model
{
    /** @use HasFactory<\Database\Factories\SaranaUmumPreventiveMaintenanceFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sarana_umum_id',
        'created_by',
        'nama_tugas',
        'deskripsi',
        'interval_hari',
        'toleransi_hari',
        'tanggal_mulai',
        'tanggal_maintenance_terakhir',
        'tanggal_maintenance_berikutnya',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'interval_hari' => 'integer',
            'toleransi_hari' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_maintenance_terakhir' => 'date',
            'tanggal_maintenance_berikutnya' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function saranaUmum(): BelongsTo
    {
        return $this->belongsTo(SaranaUmum::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->is_active && $this->tanggal_maintenance_berikutnya->isPast();
    }
}
