<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuArsipDokumen extends Model
{
    /** @use HasFactory<\Database\Factories\TuArsipDokumenFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'module',
        'source_type',
        'source_id',
        'judul',
        'nomor_dokumen',
        'tanggal_dokumen',
        'status_sumber',
        'tags',
        'metadata',
        'version',
        'retensi_sampai',
        'archived_at',
        'archived_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'metadata' => 'array',
            'tanggal_dokumen' => 'date',
            'retensi_sampai' => 'date',
            'archived_at' => 'datetime',
        ];
    }

    public function archiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
