<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultUsers = [
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'staff',
            ],
            [
                'name' => 'Pejabat TU',
                'email' => 'pejabat@example.com',
                'role' => 'pejabat',
            ],
            [
                'name' => 'Laboran Utama',
                'email' => 'laboran@example.com',
                'role' => 'laboran',
            ],
            [
                'name' => 'Staff TU',
                'email' => 'staff@example.com',
                'role' => 'staff',
            ],
            [
                'name' => 'Guru Default',
                'email' => 'guru@example.com',
                'role' => 'guru',
            ],
        ];

        foreach ($defaultUsers as $defaultUser) {
            User::query()->updateOrCreate(
                ['email' => $defaultUser['email']],
                [
                    'name' => $defaultUser['name'],
                    'role' => $defaultUser['role'],
                    'password' => Hash::make('password'),
                    'profile_photo' => null,
                    'phone_number' => null,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
