<?php

use App\Models\Laboran;
use App\Models\Laboratorium;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->laboran()->create();
    $this->actingAs($this->user);
});

test('can view laboratorium index page', function () {
    Laboratorium::factory()->count(3)->create();

    $response = $this->get(route('laboran.laboratorium.index'));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.laboratorium.index');
    $response->assertViewHas('laboratoriums');
});

test('can view create laboratorium page', function () {
    $response = $this->get(route('laboran.laboratorium.create'));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.laboratorium.create');
    $response->assertViewHas('petugasLaboran');
});

test('can store new laboratorium', function () {
    $petugas = Laboran::factory()->aktif()->create();

    $data = [
        'kode' => 'LAB-RPL-01',
        'nama' => 'Laboratorium RPL 1',
        'lokasi' => 'Gedung A Lt. 2',
        'kapasitas' => 30,
        'status' => 'aktif',
        'jurusan' => 'RPL',
        'penanggung_jawab_id' => $petugas->id,
        'fasilitas' => ['AC', 'Proyektor', 'WiFi'],
    ];

    $response = $this->post(route('laboran.laboratorium.store'), $data);

    $response->assertRedirect(route('laboran.laboratorium.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('laboratoriums', [
        'kode' => 'LAB-RPL-01',
        'nama' => 'Laboratorium RPL 1',
        'jurusan' => 'RPL',
    ]);
});

test('can view laboratorium detail', function () {
    $laboratorium = Laboratorium::factory()->create();

    $response = $this->get(route('laboran.laboratorium.show', $laboratorium));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.laboratorium.show');
    $response->assertViewHas('laboratorium');
});

test('can view edit laboratorium page', function () {
    $laboratorium = Laboratorium::factory()->create();

    $response = $this->get(route('laboran.laboratorium.edit', $laboratorium));

    $response->assertStatus(200);
    $response->assertViewIs('laboran.laboratorium.edit');
    $response->assertViewHas('laboratorium');
    $response->assertViewHas('petugasLaboran');
});

test('can update laboratorium', function () {
    $laboratorium = Laboratorium::factory()->create();

    $data = [
        'kode' => $laboratorium->kode,
        'nama' => 'Updated Lab Name',
        'lokasi' => 'Gedung B Lt. 1',
        'kapasitas' => 40,
        'status' => 'renovasi',
        'jurusan' => 'TKJ',
    ];

    $response = $this->put(route('laboran.laboratorium.update', $laboratorium), $data);

    $response->assertRedirect(route('laboran.laboratorium.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('laboratoriums', [
        'id' => $laboratorium->id,
        'nama' => 'Updated Lab Name',
        'status' => 'renovasi',
        'jurusan' => 'TKJ',
    ]);
});

test('can delete laboratorium', function () {
    $laboratorium = Laboratorium::factory()->create();

    $response = $this->delete(route('laboran.laboratorium.destroy', $laboratorium));

    $response->assertRedirect(route('laboran.laboratorium.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('laboratoriums', ['id' => $laboratorium->id]);
});

test('validates required fields when storing laboratorium', function () {
    $response = $this->post(route('laboran.laboratorium.store'), []);

    $response->assertSessionHasErrors(['kode', 'nama', 'lokasi', 'kapasitas', 'status', 'jurusan']);
});

test('validates unique kode', function () {
    $existing = Laboratorium::factory()->create();

    $response = $this->post(route('laboran.laboratorium.store'), [
        'kode' => $existing->kode,
        'nama' => 'Test Lab',
        'lokasi' => 'Test Location',
        'kapasitas' => 30,
        'status' => 'aktif',
        'jurusan' => 'RPL',
    ]);

    $response->assertSessionHasErrors(['kode']);
});

test('validates jurusan enum values', function () {
    $response = $this->post(route('laboran.laboratorium.store'), [
        'kode' => 'LAB-TEST-01',
        'nama' => 'Test Lab',
        'lokasi' => 'Test Location',
        'kapasitas' => 30,
        'status' => 'aktif',
        'jurusan' => 'INVALID',
    ]);

    $response->assertSessionHasErrors(['jurusan']);
});

test('laboratorium belongs to penanggung jawab', function () {
    $petugas = Laboran::factory()->aktif()->create();
    $laboratorium = Laboratorium::factory()->create([
        'penanggung_jawab_id' => $petugas->id,
    ]);

    expect($laboratorium->penanggungJawab)->toBeInstanceOf(Laboran::class);
    expect($laboratorium->penanggungJawab->id)->toBe($petugas->id);
});
