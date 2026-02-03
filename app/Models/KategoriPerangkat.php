<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPerangkat extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriPerangkatFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'icon',
        'status',
    ];

    /**
     * Get the komponen perangkat for this kategori.
     */
    public function komponenPerangkats(): HasMany
    {
        return $this->hasMany(KomponenPerangkat::class, 'kategori_id');
    }
}
