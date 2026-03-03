<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaranaUmumBeritaAcara extends Model
{
    /** @use HasFactory<\Database\Factories\SaranaUmumBeritaAcaraFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sarana_umum_id',
        'ruangan_id',
        'user_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'nama_guru',
        'mata_pelajaran',
        'kelas',
        'jumlah_peserta',
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
            'jumlah_peserta' => 'integer',
        ];
    }

    public function saranaUmum(): BelongsTo
    {
        return $this->belongsTo(SaranaUmum::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Laboratorium::class, 'ruangan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
