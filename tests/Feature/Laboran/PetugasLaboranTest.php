<?php

use App\Models\Laboran;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->laboran()->create();
    $this->actingAs($this->user);
});

test('can view petugas laboran index page', function () {
    Laboran::factory()->count(3)->create();

    $response = $this->get(route('laboran.petugas.index'));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.petugas.index');
    $response->assertViewHas('petugasLaboran');
});

test('can view create petugas laboran page', function () {
    $response = $this->get(route('laboran.petugas.create'));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.petugas.create');
});

test('can store new petugas laboran', function () {
    $data = [
        'nip' => '1234567890',
        'nama' => 'Test Laboran',
        'email' => 'test@example.com',
        'no_telepon' => '08123456789',
        'status' => 'aktif',
    ];

    $response = $this->post(route('laboran.petugas.store'), $data);

    $response->assertRedirect(route('laboran.petugas.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('laborans', [
        'nip' => '1234567890',
        'email' => 'test@example.com',
    ]);
});

test('can view petugas laboran detail', function () {
    $petugas = Laboran::factory()->create();

    $response = $this->get(route('laboran.petugas.show', $petugas));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.petugas.show');
    $response->assertViewHas('petugas');
});

test('can view edit petugas laboran page', function () {
    $petugas = Laboran::factory()->create();

    $response = $this->get(route('laboran.petugas.edit', $petugas));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.petugas.edit');
    $response->assertViewHas('petugas');
});

test('can update petugas laboran', function () {
    $petugas = Laboran::factory()->create();

    $data = [
        'nip' => $petugas->nip,
        'nama' => 'Updated Name',
        'email' => $petugas->email,
        'status' => 'nonaktif',
    ];

    $response = $this->put(route('laboran.petugas.update', $petugas), $data);

    $response->assertRedirect(route('laboran.petugas.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('laborans', [
        'id' => $petugas->id,
        'nama' => 'Updated Name',
        'status' => 'nonaktif',
    ]);
});

test('can delete petugas laboran', function () {
    $petugas = Laboran::factory()->create();

    $response = $this->delete(route('laboran.petugas.destroy', $petugas));

    $response->assertRedirect(route('laboran.petugas.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('laborans', ['id' => $petugas->id]);
});

test('validates required fields when storing petugas', function () {
    $response = $this->post(route('laboran.petugas.store'), []);

    $response->assertSessionHasErrors(['nip', 'nama', 'email', 'status']);
});

test('validates unique nip and email', function () {
    $existing = Laboran::factory()->create();

    $response = $this->post(route('laboran.petugas.store'), [
        'nip' => $existing->nip,
        'nama' => 'Test',
        'email' => $existing->email,
        'status' => 'aktif',
    ]);

    $response->assertSessionHasErrors(['nip', 'email']);
});
