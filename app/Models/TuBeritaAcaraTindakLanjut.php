<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuBeritaAcaraTindakLanjut extends Model
{
    /** @use HasFactory<\Database\Factories\TuBeritaAcaraTindakLanjutFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'source_type',
        'source_id',
        'status',
        'catatan',
        'tags',
        'processed_by',
        'processed_at',
        'archived_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'processed_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
