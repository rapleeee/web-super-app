<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TuArsipDokumen>
 */
class TuArsipDokumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module' => fake()->randomElement(['tu-surat', 'tu-berita-acara-final']),
            'source_type' => 'faker-source',
            'source_id' => fake()->numberBetween(1, 1000),
            'judul' => fake()->sentence(4),
            'nomor_dokumen' => fake()->optional()->numerify('####/TU/XII/2026'),
            'tanggal_dokumen' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'status_sumber' => fake()->randomElement(['draft', 'review', 'final', 'arsip', 'baru', 'diproses', 'selesai']),
            'tags' => ['arsip', 'tu'],
            'metadata' => ['keterangan' => fake()->sentence()],
            'version' => fake()->numberBetween(1, 4),
            'retensi_sampai' => fake()->dateTimeBetween('now', '+5 years')->format('Y-m-d'),
            'archived_at' => null,
            'archived_by' => null,
        ];
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status_sumber' => 'arsip',
            'archived_at' => now(),
            'archived_by' => User::factory(),
        ]);
    }
}
