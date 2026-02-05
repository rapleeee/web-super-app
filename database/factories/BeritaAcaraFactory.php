<?php

namespace Database\Factories;

use App\Models\BeritaAcara;
use App\Models\Laboratorium;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BeritaAcara>
 */
class BeritaAcaraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $waktuMulai = fake()->time('H:i');
        $waktuSelesai = date('H:i', strtotime($waktuMulai) + (fake()->numberBetween(1, 3) * 3600));

        return [
            'laboratorium_id' => Laboratorium::factory(),
            'user_id' => User::factory(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'nama_guru' => fake()->name(),
            'mata_pelajaran' => fake()->randomElement(['Pemrograman Web', 'Basis Data', 'Jaringan Komputer', 'Desain Grafis', 'Sistem Operasi']),
            'kelas' => fake()->randomElement(['X', 'XI', 'XII']).' '.fake()->randomElement(['RPL', 'TKJ', 'DKV']).' '.fake()->numberBetween(1, 3),
            'jumlah_siswa' => fake()->numberBetween(20, 36),
            'jumlah_pc_digunakan' => fake()->numberBetween(15, 30),
            'alat_tambahan' => fake()->randomElements(BeritaAcara::alatTambahanOptions(), fake()->numberBetween(0, 4)),
            'kegiatan' => fake()->optional()->paragraph(),
            'catatan' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['draft', 'final']),
        ];
    }

    /**
     * Indicate that the berita acara is final.
     */
    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'final',
        ]);
    }

    /**
     * Indicate that the berita acara is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
