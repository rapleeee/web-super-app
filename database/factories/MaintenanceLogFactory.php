<?php

namespace Database\Factories;

use App\Models\KomponenPerangkat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceLog>
 */
class MaintenanceLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalLapor = $this->faker->dateTimeBetween('-3 months', 'now');
        $status = $this->faker->randomElement(['pending', 'proses', 'selesai', 'tidak_bisa_diperbaiki']);
        $kondisiSebelum = $this->faker->randomElement(['rusak_ringan', 'rusak_berat', 'mati_total']);

        return [
            'komponen_perangkat_id' => KomponenPerangkat::factory(),
            'pelapor_id' => User::factory(),
            'tanggal_lapor' => $tanggalLapor,
            'tanggal_mulai' => $status !== 'pending' ? $this->faker->dateTimeBetween($tanggalLapor, 'now') : null,
            'tanggal_selesai' => $status === 'selesai' ? $this->faker->dateTimeBetween($tanggalLapor, 'now') : null,
            'keluhan' => $this->faker->sentence(),
            'diagnosa' => $status !== 'pending' ? $this->faker->optional()->sentence() : null,
            'tindakan' => $status === 'selesai' ? $this->faker->sentence() : null,
            'teknisi' => $this->faker->optional()->name(),
            'biaya' => $this->faker->optional()->numberBetween(50000, 2000000),
            'status' => $status,
            'kondisi_sebelum' => $kondisiSebelum,
            'kondisi_sesudah' => $status === 'selesai' ? 'baik' : null,
            'catatan' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the maintenance is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);
    }

    /**
     * Indicate that the maintenance is completed.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'kondisi_sesudah' => 'baik',
        ]);
    }
}
