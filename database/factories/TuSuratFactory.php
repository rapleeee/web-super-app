<?php

namespace Database\Factories;

use App\Models\TuSuratTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TuSurat>
 */
class TuSuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalSurat = fake()->dateTimeBetween('-15 days', '+5 days');

        return [
            'tu_surat_template_id' => TuSuratTemplate::factory(),
            'created_by' => User::factory(),
            'reviewed_by' => null,
            'approved_by' => null,
            'nomor_surat' => null,
            'perihal' => 'Perihal '.ucfirst(fake()->words(2, true)),
            'tujuan' => fake()->company(),
            'tanggal_surat' => $tanggalSurat->format('Y-m-d'),
            'isi_surat' => fake()->paragraphs(2, true),
            'status' => 'draft',
            'verification_token' => null,
            'finalized_at' => null,
            'archived_at' => null,
        ];
    }

    public function review(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'review',
            'reviewed_by' => User::factory(),
        ]);
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'final',
            'nomor_surat' => '0001/TU/'.now()->format('m').'/'.now()->format('Y'),
            'approved_by' => User::factory(),
            'verification_token' => fake()->sha1(),
            'finalized_at' => now(),
            'tanggal_surat' => now()->toDateString(),
        ]);
    }

    public function arsip(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'arsip',
            'nomor_surat' => '0002/TU/'.now()->format('m').'/'.now()->format('Y'),
            'approved_by' => User::factory(),
            'verification_token' => fake()->sha1(),
            'finalized_at' => now()->subDay(),
            'archived_at' => now(),
            'tanggal_surat' => now()->subDay()->toDateString(),
        ]);
    }
}
