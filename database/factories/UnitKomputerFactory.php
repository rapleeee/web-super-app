<?php

namespace Database\Factories;

use App\Models\Laboratorium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitKomputer>
 */
class UnitKomputerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_unit' => 'PC-' . $this->faker->unique()->numerify('###'),
            'nama' => 'Komputer ' . $this->faker->unique()->numberBetween(1, 999),
            'laboratorium_id' => Laboratorium::factory(),
            'nomor_meja' => $this->faker->numberBetween(1, 40),
            'kondisi' => $this->faker->randomElement(['baik', 'rusak_ringan', 'rusak_berat', 'mati_total']),
            'status' => $this->faker->randomElement(['aktif', 'dalam_perbaikan', 'tidak_aktif']),
            'keterangan' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the unit is in good condition.
     */
    public function baik(): static
    {
        return $this->state(fn (array $attributes) => [
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);
    }

    /**
     * Indicate that the unit is being repaired.
     */
    public function dalamPerbaikan(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'dalam_perbaikan',
        ]);
    }
}
