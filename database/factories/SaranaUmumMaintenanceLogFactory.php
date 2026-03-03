<?php

namespace Database\Factories;

use App\Models\SaranaUmum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaranaUmumMaintenanceLog>
 */
class SaranaUmumMaintenanceLogFactory extends Factory
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
            'pelapor_id' => User::factory(),
            'tanggal_lapor' => now()->toDateString(),
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => null,
            'keluhan' => fake()->sentence(),
            'diagnosa' => fake()->optional()->sentence(),
            'tindakan' => fake()->optional()->sentence(),
            'teknisi' => fake()->optional()->name(),
            'biaya' => fake()->optional()->numberBetween(10000, 500000),
            'status' => fake()->randomElement(['pending', 'proses', 'selesai']),
            'kondisi_sebelum' => fake()->randomElement(['baik', 'rusak_ringan', 'rusak_berat', 'mati_total']),
            'kondisi_sesudah' => fake()->optional()->randomElement(['baik', 'rusak_ringan', 'rusak_berat', 'mati_total']),
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
