<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    /** @use HasFactory<\Database\Factories\KelasFactory> */
    use HasFactory;

    protected $fillable = [
        'tingkat',
        'jurusan',
        'rombel',
        'status',
    ];

    /**
     * Scope untuk filter kelas aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Accessor untuk nama display (X RPL 1, XI DKV 2, dll).
     */
    protected function namaLengkap(): Attribute
    {
        return Attribute::get(function () {
            $tingkatRomawi = match ($this->tingkat) {
                '10' => 'X',
                '11' => 'XI',
                '12' => 'XII',
                default => $this->tingkat,
            };

            return "{$tingkatRomawi} {$this->jurusan} {$this->rombel}";
        });
    }

    /**
     * Available tingkat options.
     */
    public static function tingkatOptions(): array
    {
        return ['10', '11', '12'];
    }

    /**
     * Available jurusan options.
     */
    public static function jurusanOptions(): array
    {
        return ['RPL', 'DKV', 'TKJ'];
    }

    /**
     * Available rombel options.
     */
    public static function rombelOptions(): array
    {
        return ['1', '2', '3'];
    }
}
