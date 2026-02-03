<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratorium extends Model
{
    /** @use HasFactory<\Database\Factories\LaboratoriumFactory> */
    use HasFactory;

    protected $table = 'laboratoriums';

    protected $fillable = [
        'kode',
        'nama',
        'lokasi',
        'kapasitas',
        'status',
        'deskripsi',
        'jurusan',
        'penanggung_jawab_id',
        'fasilitas',
        'foto',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fasilitas' => 'array',
            'kapasitas' => 'integer',
        ];
    }

    /**
     * Get the penanggung jawab (laboran) of this laboratorium.
     */
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(Laboran::class, 'penanggung_jawab_id');
    }

    /**
     * Get the unit komputers for this laboratorium.
     */
    public function unitKomputers(): HasMany
    {
        return $this->hasMany(UnitKomputer::class);
    }
}
