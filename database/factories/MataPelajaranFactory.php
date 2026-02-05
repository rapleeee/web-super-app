<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataPelajaran>
 */
class MataPelajaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'Pemrograman Web',
            'Basis Data',
            'Pemrograman Berorientasi Objek',
            'Sistem Komputer',
            'Komputer dan Jaringan Dasar',
            'Desain Grafis',
            'Animasi 2D dan 3D',
            'Fotografi',
            'Videografi',
            'Administrasi Infrastruktur Jaringan',
            'Teknologi Layanan Jaringan',
            'Administrasi Sistem Jaringan',
        ];

        return [
            'kode' => $this->faker->unique()->bothify('MP-###'),
            'nama' => $this->faker->unique()->randomElement($subjects),
            'deskripsi' => $this->faker->sentence(),
            'status' => 'aktif',
        ];
    }

    /**
     * Indicate that the mata pelajaran is nonaktif.
     */
    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'nonaktif',
        ]);
    }
}
