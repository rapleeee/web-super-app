<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcara extends Model
{
    /** @use HasFactory<\Database\Factories\BeritaAcaraFactory> */
    use HasFactory;

    protected $fillable = [
        'laboratorium_id',
        'user_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'nama_guru',
        'mata_pelajaran',
        'kelas',
        'jumlah_siswa',
        'jumlah_pc_digunakan',
        'alat_tambahan',
        'kegiatan',
        'catatan',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'alat_tambahan' => 'array',
            'jumlah_siswa' => 'integer',
            'jumlah_pc_digunakan' => 'integer',
        ];
    }

    /**
     * Available additional equipment options.
     *
     * @return array<string>
     */
    public static function alatTambahanOptions(): array
    {
        return [
            'Kamera',
            'Headset',
            'Pen Tablet',
            'Sound',
            'Proyektor',
            'Router Board',
            'Access Point',
        ];
    }

    /**
     * Get the laboratorium.
     */
    public function laboratorium(): BelongsTo
    {
        return $this->belongsTo(Laboratorium::class);
    }

    /**
     * Get the user who created this record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
