<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IzinKaryawan extends Model
{
    /** @use HasFactory<\Database\Factories\IzinKaryawanFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nama_karyawan',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'dinas_luar_hari',
        'dinas_luar_waktu',
        'dinas_luar_tempat',
        'lampiran',
        'status',
        'approved_by',
        'approved_at',
        'catatan_persetujuan',
        'surat_tugas_nomor',
        'surat_tugas_sebagai',
        'surat_tugas_diterbitkan_at',
        'surat_tugas_signed_at',
        'surat_tugas_signature_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'approved_at' => 'datetime',
            'surat_tugas_diterbitkan_at' => 'datetime',
            'surat_tugas_signed_at' => 'datetime',
        ];
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getDurasiHariAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    public function getNomorPengajuanAttribute(): string
    {
        $datePrefix = $this->created_at?->format('Ymd') ?? now()->format('Ymd');

        return 'IZN-'.$datePrefix.'-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getHariMenungguAttribute(): int
    {
        if ($this->status !== 'diajukan') {
            return 0;
        }

        return $this->created_at->startOfDay()->diffInDays(now()->startOfDay());
    }

    public function hasSuratTugas(): bool
    {
        return $this->jenis === 'dinas_luar'
            && $this->status === 'disetujui'
            && ! empty($this->surat_tugas_nomor);
    }
}
