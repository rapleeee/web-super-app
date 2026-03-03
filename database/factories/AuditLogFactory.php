<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'module' => fake()->randomElement(['sarana-umum', 'maintenance-log', 'berita-acara', 'backup']),
            'action' => fake()->randomElement(['create', 'update', 'delete', 'import', 'export']),
            'auditable_type' => null,
            'auditable_id' => null,
            'before_data' => null,
            'after_data' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'url' => fake()->url(),
            'created_at' => now(),
        ];
    }
}
