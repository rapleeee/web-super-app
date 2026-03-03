<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IzinKaryawan>
 */
class IzinKaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-20 days', '+5 days');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(0, 3).' days');

        return [
            'user_id' => User::factory(),
            'nama_karyawan' => fake()->name(),
            'jenis' => fake()->randomElement(['izin', 'cuti', 'sakit']),
            'tanggal_mulai' => $startDate->format('Y-m-d'),
            'tanggal_selesai' => $endDate->format('Y-m-d'),
            'alasan' => fake()->sentence(),
            'dinas_luar_hari' => null,
            'dinas_luar_waktu' => null,
            'dinas_luar_tempat' => null,
            'lampiran' => null,
            'status' => fake()->randomElement(['diajukan', 'disetujui', 'ditolak']),
            'approved_by' => null,
            'approved_at' => null,
            'catatan_persetujuan' => null,
            'surat_tugas_nomor' => null,
            'surat_tugas_sebagai' => null,
            'surat_tugas_diterbitkan_at' => null,
            'surat_tugas_signed_at' => null,
            'surat_tugas_signature_token' => null,
        ];
    }

    public function dinasLuar(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'jenis' => 'dinas_luar',
                'dinas_luar_hari' => fake()->dayOfWeek(),
                'dinas_luar_waktu' => fake()->randomElement(['07.00 - 12.00 WIB', '08.00 - 16.00 WIB']),
                'dinas_luar_tempat' => fake()->city(),
            ];
        });
    }
}
