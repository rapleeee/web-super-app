<?php

use App\Models\Guru;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('guru index page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.data-master.guru.index'));

    $response->assertOk();
});

test('guru create page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.data-master.guru.create'));

    $response->assertOk();
});

test('guru can be created', function () {
    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.data-master.guru.store'), [
            'nip' => '198501012010011001',
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'no_telepon' => '081234567890',
            'status' => 'aktif',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('laboran.data-master.guru.index'));

    $this->assertDatabaseHas('gurus', [
        'nip' => '198501012010011001',
        'nama' => 'Budi Santoso',
    ]);
});

test('guru can be updated', function () {
    $guru = Guru::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->put(route('laboran.data-master.guru.update', $guru), [
            'nama' => 'Updated Name',
            'status' => 'nonaktif',
        ]);

    $response->assertRedirect(route('laboran.data-master.guru.index'));

    $this->assertDatabaseHas('gurus', [
        'id' => $guru->id,
        'nama' => 'Updated Name',
        'status' => 'nonaktif',
    ]);
});

test('guru can be deleted', function () {
    $guru = Guru::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->delete(route('laboran.data-master.guru.destroy', $guru));

    $response->assertRedirect(route('laboran.data-master.guru.index'));

    $this->assertDatabaseMissing('gurus', ['id' => $guru->id]);
});

test('guru aktif scope returns only active gurus', function () {
    Guru::factory()->count(3)->create(['status' => 'aktif']);
    Guru::factory()->count(2)->create(['status' => 'nonaktif']);

    expect(Guru::aktif()->count())->toBe(3);
});
