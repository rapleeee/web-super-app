<?php

use App\Models\MataPelajaran;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('mata pelajaran index page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.data-master.mata-pelajaran.index'));

    $response->assertOk();
});

test('mata pelajaran create page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.data-master.mata-pelajaran.create'));

    $response->assertOk();
});

test('mata pelajaran can be created', function () {
    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.data-master.mata-pelajaran.store'), [
            'kode' => 'MP-001',
            'nama' => 'Pemrograman Web',
            'deskripsi' => 'Mata pelajaran tentang pemrograman web',
            'status' => 'aktif',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('laboran.data-master.mata-pelajaran.index'));

    $this->assertDatabaseHas('mata_pelajarans', [
        'kode' => 'MP-001',
        'nama' => 'Pemrograman Web',
    ]);
});

test('mata pelajaran can be updated', function () {
    $mapel = MataPelajaran::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->put(route('laboran.data-master.mata-pelajaran.update', $mapel), [
            'nama' => 'Updated Mapel',
            'status' => 'nonaktif',
        ]);

    $response->assertRedirect(route('laboran.data-master.mata-pelajaran.index'));

    $this->assertDatabaseHas('mata_pelajarans', [
        'id' => $mapel->id,
        'nama' => 'Updated Mapel',
        'status' => 'nonaktif',
    ]);
});

test('mata pelajaran can be deleted', function () {
    $mapel = MataPelajaran::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->delete(route('laboran.data-master.mata-pelajaran.destroy', $mapel));

    $response->assertRedirect(route('laboran.data-master.mata-pelajaran.index'));

    $this->assertDatabaseMissing('mata_pelajarans', ['id' => $mapel->id]);
});
