<?php

use App\Models\BeritaAcara;
use App\Models\Laboratorium;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('berita acara index page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.berita-acara.index'));

    $response->assertOk();
});

test('berita acara create page is displayed', function () {
    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.berita-acara.create'));

    $response->assertOk();
});

test('berita acara can be created', function () {
    $lab = Laboratorium::factory()->create(['status' => 'aktif']);

    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.berita-acara.store'), [
            'laboratorium_id' => $lab->id,
            'tanggal' => '2026-02-04',
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '10:00',
            'nama_guru' => 'Budi Santoso',
            'mata_pelajaran' => 'Pemrograman Web',
            'kelas' => 'XII RPL 1',
            'jumlah_siswa' => 30,
            'jumlah_pc_digunakan' => 15,
            'alat_tambahan' => ['Proyektor', 'Headset'],
            'kegiatan' => 'Praktikum Laravel',
            'status' => 'draft',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('laboran.berita-acara.index'));

    $this->assertDatabaseHas('berita_acaras', [
        'nama_guru' => 'Budi Santoso',
        'kelas' => 'XII RPL 1',
        'jumlah_siswa' => 30,
    ]);
});

test('berita acara show page is displayed', function () {
    $beritaAcara = BeritaAcara::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.berita-acara.show', $beritaAcara));

    $response->assertOk();
});

test('berita acara edit page is displayed', function () {
    $beritaAcara = BeritaAcara::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.berita-acara.edit', $beritaAcara));

    $response->assertOk();
});

test('berita acara can be updated', function () {
    $beritaAcara = BeritaAcara::factory()->create();
    $lab = Laboratorium::factory()->create(['status' => 'aktif']);

    $response = $this
        ->actingAs($this->user)
        ->put(route('laboran.berita-acara.update', $beritaAcara), [
            'laboratorium_id' => $lab->id,
            'tanggal' => '2026-02-05',
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
            'nama_guru' => 'Siti Aminah',
            'kelas' => 'XI TKJ 2',
            'jumlah_siswa' => 25,
            'jumlah_pc_digunakan' => 12,
            'status' => 'final',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('laboran.berita-acara.show', $beritaAcara));

    $beritaAcara->refresh();
    expect($beritaAcara->nama_guru)->toBe('Siti Aminah');
    expect($beritaAcara->status)->toBe('final');
});

test('berita acara can be deleted', function () {
    $beritaAcara = BeritaAcara::factory()->create();

    $response = $this
        ->actingAs($this->user)
        ->delete(route('laboran.berita-acara.destroy', $beritaAcara));

    $response->assertRedirect(route('laboran.berita-acara.index'));

    $this->assertDatabaseMissing('berita_acaras', [
        'id' => $beritaAcara->id,
    ]);
});

test('berita acara requires valid data', function () {
    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.berita-acara.store'), []);

    $response->assertSessionHasErrors([
        'laboratorium_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'nama_guru',
        'kelas',
        'jumlah_siswa',
        'jumlah_pc_digunakan',
        'status',
    ]);
});

test('waktu selesai must be after waktu mulai', function () {
    $lab = Laboratorium::factory()->create(['status' => 'aktif']);

    $response = $this
        ->actingAs($this->user)
        ->post(route('laboran.berita-acara.store'), [
            'laboratorium_id' => $lab->id,
            'tanggal' => '2026-02-04',
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '08:00',
            'nama_guru' => 'Test Guru',
            'kelas' => 'X RPL 1',
            'jumlah_siswa' => 20,
            'jumlah_pc_digunakan' => 10,
            'status' => 'draft',
        ]);

    $response->assertSessionHasErrors(['waktu_selesai']);
});

test('berita acara can be exported to csv', function () {
    $lab = Laboratorium::factory()->create(['status' => 'aktif']);

    // Create final status berita acaras
    BeritaAcara::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'laboratorium_id' => $lab->id,
        'status' => 'final',
        'tanggal' => now()->format('Y-m-d'),
    ]);

    // Create draft status (should not be exported)
    BeritaAcara::factory()->create([
        'user_id' => $this->user->id,
        'laboratorium_id' => $lab->id,
        'status' => 'draft',
        'tanggal' => now()->format('Y-m-d'),
    ]);

    $startDate = now()->startOfMonth()->format('Y-m-d');
    $endDate = now()->endOfMonth()->format('Y-m-d');

    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.berita-acara.export', [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    // Verify filename format includes date range
    $expectedFilename = 'berita-acara-'.str_replace('-', '', $startDate).'-'.str_replace('-', '', $endDate).'.csv';
    $response->assertHeader('Content-Disposition', 'attachment; filename="'.$expectedFilename.'"');
});

test('berita acara export can filter by laboratorium', function () {
    $lab1 = Laboratorium::factory()->create(['status' => 'aktif', 'nama' => 'Lab A']);
    $lab2 = Laboratorium::factory()->create(['status' => 'aktif', 'nama' => 'Lab B']);

    BeritaAcara::factory()->create([
        'user_id' => $this->user->id,
        'laboratorium_id' => $lab1->id,
        'status' => 'final',
        'tanggal' => now()->format('Y-m-d'),
    ]);

    BeritaAcara::factory()->create([
        'user_id' => $this->user->id,
        'laboratorium_id' => $lab2->id,
        'status' => 'final',
        'tanggal' => now()->format('Y-m-d'),
    ]);

    $response = $this
        ->actingAs($this->user)
        ->get(route('laboran.berita-acara.export', [
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->endOfMonth()->format('Y-m-d'),
            'laboratorium_id' => $lab1->id,
        ]));

    $response->assertOk();

    // Content should only contain Lab A data
    $content = $response->getContent();
    expect($content)->toContain($lab1->nama);
});
