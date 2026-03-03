<?php

namespace Database\Factories;

use App\Models\Laboratorium;
use App\Models\SaranaUmum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaranaUmumBeritaAcara>
 */
class SaranaUmumBeritaAcaraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sarana_umum_id' => SaranaUmum::factory(),
            'ruangan_id' => Laboratorium::factory(),
            'user_id' => User::factory(),
            'tanggal' => now()->toDateString(),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '10:00',
            'nama_guru' => fake()->name(),
            'mata_pelajaran' => fake()->randomElement(['Informatika', 'Multimedia', 'Jaringan']),
            'kelas' => fake()->randomElement(['X RPL 1', 'XI TKJ 2', 'XII DKV 1']),
            'jumlah_peserta' => fake()->numberBetween(10, 40),
            'kegiatan' => fake()->optional()->sentence(),
            'catatan' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['draft', 'final']),
        ];
    }
}
