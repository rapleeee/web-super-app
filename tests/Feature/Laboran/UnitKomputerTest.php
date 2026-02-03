<?php

use App\Models\Laboratorium;
use App\Models\UnitKomputer;
use App\Models\User;

beforeEach(function () {
    $this->laboran = User::factory()->create(['role' => 'laboran']);
    $this->laboratorium = Laboratorium::factory()->create();
});

test('unit komputer index can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.index'));

    $response->assertStatus(200);
});

test('unit komputer create can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.create'));

    $response->assertStatus(200);
});

test('unit komputer can be created', function () {
    $response = $this->actingAs($this->laboran)
        ->post(route('laboran.unit-komputer.store'), [
            'kode_unit' => 'PC-001',
            'nama' => 'Komputer 1',
            'laboratorium_id' => $this->laboratorium->id,
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

    $response->assertRedirect(route('laboran.unit-komputer.index'));
    $this->assertDatabaseHas('unit_komputers', [
        'kode_unit' => 'PC-001',
        'nama' => 'Komputer 1',
    ]);
});

test('unit komputer show can be rendered', function () {
    $unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.show', $unit));

    $response->assertStatus(200);
});

test('unit komputer edit can be rendered', function () {
    $unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.edit', $unit));

    $response->assertStatus(200);
});

test('unit komputer can be updated', function () {
    $unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);

    $response = $this->actingAs($this->laboran)
        ->put(route('laboran.unit-komputer.update', $unit), [
            'kode_unit' => 'PC-002',
            'nama' => 'Komputer 2',
            'laboratorium_id' => $this->laboratorium->id,
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

    $response->assertRedirect(route('laboran.unit-komputer.index'));
    $this->assertDatabaseHas('unit_komputers', [
        'id' => $unit->id,
        'kode_unit' => 'PC-002',
    ]);
});

test('unit komputer can be deleted', function () {
    $unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);

    $response = $this->actingAs($this->laboran)
        ->delete(route('laboran.unit-komputer.destroy', $unit));

    $response->assertRedirect(route('laboran.unit-komputer.index'));
    $this->assertDatabaseMissing('unit_komputers', [
        'id' => $unit->id,
    ]);
});
