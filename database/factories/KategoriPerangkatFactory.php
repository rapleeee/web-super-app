<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriPerangkat>
 */
class KategoriPerangkatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement(['PC', 'Monitor', 'Keyboard', 'Mouse', 'Proyektor', 'Printer', 'Scanner', 'UPS', 'Speaker', 'Headset']);

        return [
            'kode' => 'KAT-' . $this->faker->unique()->numerify('###'),
            'nama' => $kategori,
            'deskripsi' => $this->faker->optional()->sentence(),
            'icon' => null,
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
        ];
    }

    /**
     * Indicate that the kategori is active.
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }
}
