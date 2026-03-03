<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TuSuratTemplate>
 */
class TuSuratTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => 'TPL-'.strtoupper(fake()->unique()->bothify('??##')),
            'nama' => 'Template '.fake()->words(2, true),
            'judul' => 'Surat '.ucfirst(fake()->words(3, true)),
            'isi_template' => "Dengan hormat,\n\n{{isi_utama}}\n\nDemikian surat ini disampaikan.",
            'is_active' => true,
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }
}
