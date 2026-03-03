<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaranaUmum>
 */
class SaranaUmumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_inventaris' => 'SRN-'.$this->faker->unique()->numerify('###'),
            'nama' => $this->faker->randomElement(['AC Ruangan', 'Proyektor Utama', 'CCTV Koridor', 'Sound System', 'Speaker Aktif']),
            'jenis' => $this->faker->randomElement(['AC', 'Proyektor', 'CCTV', 'Sound', 'Lainnya']),
            'lokasi' => $this->faker->randomElement(['Ruang Guru', 'Aula Sekolah', 'Koridor Lt. 2', 'Ruang TU', 'Lobby']),
            'merk' => $this->faker->optional()->company(),
            'model' => $this->faker->optional()->bothify('MDL-###'),
            'nomor_seri' => $this->faker->optional()->bothify('SN-#####'),
            'tahun_pengadaan' => $this->faker->numberBetween(2018, 2026),
            'kondisi' => $this->faker->randomElement(['baik', 'rusak_ringan', 'rusak_berat', 'mati_total']),
            'status' => $this->faker->randomElement(['aktif', 'dalam_perbaikan', 'tidak_aktif']),
            'keterangan' => $this->faker->optional()->sentence(),
            'foto' => null,
        ];
    }
}
