<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TuBeritaAcaraTindakLanjut>
 */
class TuBeritaAcaraTindakLanjutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_type' => fake()->randomElement(['laboran', 'sarana_umum']),
            'source_id' => fake()->numberBetween(1, 1000),
            'status' => fake()->randomElement(['baru', 'diproses', 'selesai', 'arsip']),
            'catatan' => fake()->sentence(),
            'tags' => ['follow-up', 'tu'],
            'processed_by' => User::factory(),
            'processed_at' => now(),
            'archived_at' => null,
        ];
    }
}
