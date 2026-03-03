<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuSurat extends Model
{
    /** @use HasFactory<\Database\Factories\TuSuratFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tu_surat_template_id',
        'created_by',
        'reviewed_by',
        'approved_by',
        'nomor_surat',
        'perihal',
        'tujuan',
        'tanggal_surat',
        'isi_surat',
        'status',
        'verification_token',
        'finalized_at',
        'archived_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'finalized_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TuSuratTemplate::class, 'tu_surat_template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canBeEditedBy(User $user): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        if ($this->created_by === $user->id) {
            return true;
        }

        return self::isPrivilegedRole($user->role);
    }

    public function canBeSubmittedBy(User $user): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        if ($this->created_by === $user->id) {
            return true;
        }

        return self::isPrivilegedRole($user->role);
    }

    public function canBeFinalizedBy(User $user): bool
    {
        if ($this->status !== 'review') {
            return false;
        }

        return self::isPrivilegedRole($user->role);
    }

    public function canBeArchivedBy(User $user): bool
    {
        if ($this->status !== 'final') {
            return false;
        }

        return self::isPrivilegedRole($user->role);
    }

    public static function generateNomorSurat(CarbonInterface $tanggalSurat): string
    {
        $tahun = (int) $tanggalSurat->format('Y');
        $bulan = (int) $tanggalSurat->format('n');
        $bulanRomawi = self::bulanToRomawi($bulan);

        $nomorUrut = self::query()
            ->whereYear('tanggal_surat', $tahun)
            ->whereNotNull('nomor_surat')
            ->count() + 1;

        do {
            $nomorSurat = sprintf('%04d/TU/%s/%d', $nomorUrut, $bulanRomawi, $tahun);
            $nomorUrut++;
        } while (self::query()->where('nomor_surat', $nomorSurat)->exists());

        return $nomorSurat;
    }

    private static function isPrivilegedRole(?string $role): bool
    {
        return in_array($role, ['admin', 'pejabat'], true);
    }

    private static function bulanToRomawi(int $bulan): string
    {
        $daftarRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $daftarRomawi[$bulan] ?? '-';
    }
}
