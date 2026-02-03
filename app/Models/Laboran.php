<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboran extends Model
{
    /** @use HasFactory<\Database\Factories\LaboranFactory> */
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_telepon',
        'status',
        'foto',
    ];

    /**
     * Get the laboratoriums managed by this laboran.
     */
    public function laboratoriums(): HasMany
    {
        return $this->hasMany(Laboratorium::class, 'penanggung_jawab_id');
    }
}
