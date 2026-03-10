<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

test('database seeder creates default users for all roles', function () {
    $this->seed(DatabaseSeeder::class);

    $expectedUsers = [
        ['email' => 'admin@example.com', 'role' => 'admin'],
        ['email' => 'test@example.com', 'role' => 'staff'],
        ['email' => 'pejabat@example.com', 'role' => 'pejabat'],
        ['email' => 'laboran@example.com', 'role' => 'laboran'],
        ['email' => 'staff@example.com', 'role' => 'staff'],
        ['email' => 'guru@example.com', 'role' => 'guru'],
    ];

    foreach ($expectedUsers as $expectedUser) {
        $this->assertDatabaseHas('users', $expectedUser);

        $user = User::query()->where('email', $expectedUser['email'])->first();
        expect($user)->not->toBeNull();
        expect(Hash::check('password', (string) $user?->password))->toBeTrue();
    }
});
