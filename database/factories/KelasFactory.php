<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tingkat' => $this->faker->randomElement(['10', '11', '12']),
            'jurusan' => $this->faker->randomElement(['RPL', 'DKV', 'TKJ']),
            'rombel' => $this->faker->randomElement(['1', '2', '3']),
            'status' => 'aktif',
        ];
    }

    /**
     * Indicate that the kelas is nonaktif.
     */
    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'nonaktif',
        ]);
    }
}
