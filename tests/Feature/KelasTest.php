<?php

use App\Models\Kelas;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('kelas index page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.data-master.kelas.index'));

    $response->assertOk();
});

test('kelas create page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.data-master.kelas.create'));

    $response->assertOk();
});

test('kelas can be created', function () {
    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.data-master.kelas.store'), [
            'tingkat' => '10',
            'jurusan' => 'RPL',
            'status' => 'aktif',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('laboran.data-master.kelas.index'));

    $this->assertDatabaseHas('kelas', [
        'tingkat' => '10',
        'jurusan' => 'RPL',
    ]);
});

test('kelas can be updated', function () {
    $kelas = Kelas::factory()->create([
        'tingkat' => '10',
        'jurusan' => 'RPL',
    ]);

    $response = $this
        ->actingAs($this->user)
        ->put(route('laboran.data-master.kelas.update', $kelas), [
            'tingkat' => '11',
            'jurusan' => 'DKV',
            'status' => 'nonaktif',
        ]);

    $response->assertRedirect(route('laboran.data-master.kelas.index'));

    $this->assertDatabaseHas('kelas', [
        'id' => $kelas->id,
        'tingkat' => '11',
        'jurusan' => 'DKV',
        'status' => 'nonaktif',
    ]);
});

test('kelas can be deleted', function () {
    $kelas = Kelas::factory()->create([
        'tingkat' => '12',
        'jurusan' => 'TKJ',
    ]);

    $response = $this
        ->actingAs($this->user)
        ->delete(route('laboran.data-master.kelas.destroy', $kelas));

    $response->assertRedirect(route('laboran.data-master.kelas.index'));

    $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
});

test('kelas nama_lengkap accessor returns correct format', function () {
    $kelas = Kelas::factory()->create([
        'tingkat' => '10',
        'jurusan' => 'RPL',
    ]);

    expect($kelas->nama_lengkap)->toBe('X RPL');

    $kelas2 = Kelas::factory()->create([
        'tingkat' => '11',
        'jurusan' => 'DKV',
    ]);

    expect($kelas2->nama_lengkap)->toBe('XI DKV');

    $kelas3 = Kelas::factory()->create([
        'tingkat' => '12',
        'jurusan' => 'TKJ',
    ]);

    expect($kelas3->nama_lengkap)->toBe('XII TKJ');
});

test('duplicate kelas cannot be created', function () {
    Kelas::factory()->create([
        'tingkat' => '10',
        'jurusan' => 'RPL',
    ]);

    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.data-master.kelas.store'), [
            'tingkat' => '10',
            'jurusan' => 'RPL',
            'status' => 'aktif',
        ]);

    $response->assertSessionHasErrors(['jurusan']);
});
