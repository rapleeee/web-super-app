<?php

namespace Database\Factories;

use App\Models\SaranaUmum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaranaUmumPreventiveMaintenance>
 */
class SaranaUmumPreventiveMaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nextDueDate = now()->addDays(fake()->numberBetween(1, 60));

        return [
            'sarana_umum_id' => SaranaUmum::factory(),
            'created_by' => User::factory(),
            'nama_tugas' => fake()->randomElement(['Servis Berkala', 'Pembersihan Unit', 'Kalibrasi', 'Pengecekan Komponen']),
            'deskripsi' => fake()->optional()->sentence(),
            'interval_hari' => fake()->randomElement([30, 60, 90, 180]),
            'toleransi_hari' => fake()->numberBetween(0, 7),
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_maintenance_terakhir' => null,
            'tanggal_maintenance_berikutnya' => $nextDueDate->toDateString(),
            'is_active' => true,
        ];
    }
}
