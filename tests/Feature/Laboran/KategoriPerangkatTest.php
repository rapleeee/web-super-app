<?php

use App\Models\KategoriPerangkat;
use App\Models\User;

beforeEach(function () {
    $this->laboran = User::factory()->create(['role' => 'laboran']);
});

test('kategori perangkat index can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.kategori-perangkat.index'));

    $response->assertStatus(200);
});

test('kategori perangkat create can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.kategori-perangkat.create'));

    $response->assertStatus(200);
});

test('kategori perangkat can be created', function () {
    $response = $this->actingAs($this->laboran)
        ->post(route('laboran.kategori-perangkat.store'), [
            'kode' => 'PC',
            'nama' => 'PC/CPU',
            'status' => 'aktif',
        ]);

    $response->assertRedirect(route('laboran.kategori-perangkat.index'));
    $this->assertDatabaseHas('kategori_perangkats', [
        'kode' => 'PC',
        'nama' => 'PC/CPU',
    ]);
});

test('kategori perangkat show can be rendered', function () {
    $kategori = KategoriPerangkat::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.kategori-perangkat.show', $kategori));

    $response->assertStatus(200);
});

test('kategori perangkat edit can be rendered', function () {
    $kategori = KategoriPerangkat::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.kategori-perangkat.edit', $kategori));

    $response->assertStatus(200);
});

test('kategori perangkat can be updated', function () {
    $kategori = KategoriPerangkat::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->put(route('laboran.kategori-perangkat.update', $kategori), [
            'kode' => 'MON',
            'nama' => 'Monitor',
            'status' => 'aktif',
        ]);

    $response->assertRedirect(route('laboran.kategori-perangkat.index'));
    $this->assertDatabaseHas('kategori_perangkats', [
        'id' => $kategori->id,
        'kode' => 'MON',
        'nama' => 'Monitor',
    ]);
});

test('kategori perangkat can be deleted', function () {
    $kategori = KategoriPerangkat::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->delete(route('laboran.kategori-perangkat.destroy', $kategori));

    $response->assertRedirect(route('laboran.kategori-perangkat.index'));
    $this->assertDatabaseMissing('kategori_perangkats', [
        'id' => $kategori->id,
    ]);
});
