<?php

use App\Models\KategoriPerangkat;
use App\Models\KomponenPerangkat;
use App\Models\Laboratorium;
use App\Models\UnitKomputer;
use App\Models\User;

beforeEach(function () {
    $this->laboran = User::factory()->create(['role' => 'laboran']);
    $this->laboratorium = Laboratorium::factory()->create();
    $this->unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);
    $this->kategori = KategoriPerangkat::factory()->create();
});

test('komponen perangkat index can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.komponen-perangkat.index'));

    $response->assertStatus(200);
});

test('komponen perangkat create can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.komponen-perangkat.create'));

    $response->assertStatus(200);
});

test('komponen perangkat can be created', function () {
    $response = $this->actingAs($this->laboran)
        ->post(route('laboran.komponen-perangkat.store'), [
            'kode_inventaris' => 'INV-001',
            'unit_komputer_id' => $this->unit->id,
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

    $response->assertRedirect(route('laboran.komponen-perangkat.index'));
    $this->assertDatabaseHas('komponen_perangkats', [
        'kode_inventaris' => 'INV-001',
    ]);
});

test('komponen perangkat show can be rendered', function () {
    $komponen = KomponenPerangkat::factory()->create([
        'unit_komputer_id' => $this->unit->id,
        'kategori_id' => $this->kategori->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.komponen-perangkat.show', $komponen));

    $response->assertStatus(200);
});

test('komponen perangkat edit can be rendered', function () {
    $komponen = KomponenPerangkat::factory()->create([
        'unit_komputer_id' => $this->unit->id,
        'kategori_id' => $this->kategori->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.komponen-perangkat.edit', $komponen));

    $response->assertStatus(200);
});

test('komponen perangkat can be updated', function () {
    $komponen = KomponenPerangkat::factory()->create([
        'unit_komputer_id' => $this->unit->id,
        'kategori_id' => $this->kategori->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->put(route('laboran.komponen-perangkat.update', $komponen), [
            'kode_inventaris' => 'INV-002',
            'unit_komputer_id' => $this->unit->id,
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'rusak_ringan',
            'status' => 'dalam_perbaikan',
        ]);

    $response->assertRedirect(route('laboran.komponen-perangkat.index'));
    $this->assertDatabaseHas('komponen_perangkats', [
        'id' => $komponen->id,
        'kode_inventaris' => 'INV-002',
    ]);
});

test('komponen perangkat can be deleted', function () {
    $komponen = KomponenPerangkat::factory()->create([
        'unit_komputer_id' => $this->unit->id,
        'kategori_id' => $this->kategori->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->delete(route('laboran.komponen-perangkat.destroy', $komponen));

    $response->assertRedirect(route('laboran.komponen-perangkat.index'));
    $this->assertDatabaseMissing('komponen_perangkats', [
        'id' => $komponen->id,
    ]);
});
