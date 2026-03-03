<?php

use App\Models\SaranaUmum;
use App\Models\User;

beforeEach(function () {
    $this->laboran = User::factory()->create(['role' => 'laboran']);
});

test('sarana umum index can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('sarana-umum.data-sarana.index'));

    $response->assertSuccessful();
});

test('sarana umum dashboard can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('sarana-umum.dashboard'));

    $response->assertSuccessful();
});

test('sarana umum create can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('sarana-umum.data-sarana.create'));

    $response->assertSuccessful();
});

test('sarana umum can be created', function () {
    $response = $this->actingAs($this->laboran)
        ->post(route('sarana-umum.data-sarana.store'), [
            'kode_inventaris' => 'SRN-001',
            'nama' => 'Proyektor Aula',
            'jenis' => 'Proyektor',
            'lokasi' => 'Aula Utama',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

    $response->assertRedirect(route('sarana-umum.data-sarana.index'));

    $this->assertDatabaseHas('sarana_umums', [
        'kode_inventaris' => 'SRN-001',
        'nama' => 'Proyektor Aula',
    ]);
});

test('sarana umum show can be rendered', function () {
    $sarana = SaranaUmum::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->get(route('sarana-umum.data-sarana.show', $sarana));

    $response->assertSuccessful();
});

test('sarana umum edit can be rendered', function () {
    $sarana = SaranaUmum::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->get(route('sarana-umum.data-sarana.edit', $sarana));

    $response->assertSuccessful();
});

test('sarana umum can be updated', function () {
    $sarana = SaranaUmum::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->put(route('sarana-umum.data-sarana.update', $sarana), [
            'kode_inventaris' => 'SRN-009',
            'nama' => 'AC Ruang Guru',
            'jenis' => 'AC',
            'lokasi' => 'Ruang Guru',
            'kondisi' => 'rusak_ringan',
            'status' => 'dalam_perbaikan',
        ]);

    $response->assertRedirect(route('sarana-umum.data-sarana.index'));

    $this->assertDatabaseHas('sarana_umums', [
        'id' => $sarana->id,
        'kode_inventaris' => 'SRN-009',
        'status' => 'dalam_perbaikan',
    ]);
});

test('sarana umum can be deleted', function () {
    $sarana = SaranaUmum::factory()->create();

    $response = $this->actingAs($this->laboran)
        ->delete(route('sarana-umum.data-sarana.destroy', $sarana));

    $response->assertRedirect(route('sarana-umum.data-sarana.index'));

    $this->assertDatabaseMissing('sarana_umums', [
        'id' => $sarana->id,
    ]);
});
