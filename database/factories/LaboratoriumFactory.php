<?php

namespace Database\Factories;

use App\Models\Laboran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laboratorium>
 */
class LaboratoriumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jurusan = $this->faker->randomElement(['DKV', 'TKJ', 'RPL']);

        return [
            'kode' => 'LAB-' . $jurusan . '-' . $this->faker->unique()->numerify('###'),
            'nama' => 'Laboratorium ' . $jurusan . ' ' . $this->faker->unique()->numberBetween(1, 999),
            'lokasi' => 'Gedung ' . $this->faker->randomElement(['A', 'B', 'C']) . ' Lt. ' . $this->faker->numberBetween(1, 3),
            'kapasitas' => $this->faker->numberBetween(20, 40),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif', 'renovasi']),
            'deskripsi' => $this->faker->optional()->sentence(),
            'jurusan' => $jurusan,
            'penanggung_jawab_id' => Laboran::factory(),
            'fasilitas' => $this->faker->randomElements(['AC', 'Proyektor', 'Whiteboard', 'Sound System', 'CCTV'], 3),
            'foto' => null,
        ];
    }

    /**
     * Indicate that the laboratorium is active.
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Indicate that the laboratorium is under renovation.
     */
    public function renovasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'renovasi',
        ]);
    }

    /**
     * Indicate that the laboratorium belongs to DKV.
     */
    public function dkv(): static
    {
        return $this->state(fn (array $attributes) => [
            'jurusan' => 'DKV',
        ]);
    }

    /**
     * Indicate that the laboratorium belongs to TKJ.
     */
    public function tkj(): static
    {
        return $this->state(fn (array $attributes) => [
            'jurusan' => 'TKJ',
        ]);
    }

    /**
     * Indicate that the laboratorium belongs to RPL.
     */
    public function rpl(): static
    {
        return $this->state(fn (array $attributes) => [
            'jurusan' => 'RPL',
        ]);
    }
}
