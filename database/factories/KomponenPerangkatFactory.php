<?php

namespace Database\Factories;

use App\Models\KategoriPerangkat;
use App\Models\UnitKomputer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KomponenPerangkat>
 */
class KomponenPerangkatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $merks = ['Lenovo', 'Dell', 'HP', 'Asus', 'Acer', 'Samsung', 'LG', 'Logitech', 'Razer'];

        return [
            'kode_inventaris' => 'INV-' . $this->faker->unique()->numerify('#####'),
            'unit_komputer_id' => UnitKomputer::factory(),
            'kategori_id' => KategoriPerangkat::factory(),
            'merk' => $this->faker->randomElement($merks),
            'model' => $this->faker->optional()->bothify('??-####'),
            'nomor_seri' => $this->faker->optional()->uuid(),
            'tahun_pengadaan' => $this->faker->optional()->numberBetween(2018, 2026),
            'kondisi' => $this->faker->randomElement(['baik', 'rusak_ringan', 'rusak_berat', 'mati_total']),
            'status' => $this->faker->randomElement(['aktif', 'dalam_perbaikan', 'tidak_aktif']),
            'spesifikasi' => $this->faker->optional()->sentence(),
            'keterangan' => $this->faker->optional()->sentence(),
            'foto' => null,
        ];
    }

    /**
     * Indicate that the komponen is in good condition.
     */
    public function baik(): static
    {
        return $this->state(fn (array $attributes) => [
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);
    }

    /**
     * Indicate that the komponen is broken.
     */
    public function rusak(): static
    {
        return $this->state(fn (array $attributes) => [
            'kondisi' => $this->faker->randomElement(['rusak_ringan', 'rusak_berat']),
            'status' => 'dalam_perbaikan',
        ]);
    }
}
