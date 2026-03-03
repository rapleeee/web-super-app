<?php

use App\Models\SaranaUmum;
use App\Models\SaranaUmumPreventiveMaintenance;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('sarana umum maintenance log can be created', function () {
    $sarana = SaranaUmum::factory()->create([
        'kondisi' => 'baik',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('sarana-umum.maintenance-log.store'), [
            'sarana_umum_id' => $sarana->id,
            'tanggal_lapor' => now()->toDateString(),
            'keluhan' => 'AC tidak dingin.',
            'status' => 'pending',
            'kondisi_sebelum' => 'rusak_ringan',
        ]);

    $response->assertRedirect(route('sarana-umum.maintenance-log.index'));

    $this->assertDatabaseHas('sarana_umum_maintenance_logs', [
        'sarana_umum_id' => $sarana->id,
        'keluhan' => 'AC tidak dingin.',
    ]);

    $this->assertDatabaseHas('sarana_umums', [
        'id' => $sarana->id,
        'status' => 'dalam_perbaikan',
        'kondisi' => 'rusak_ringan',
    ]);
});

test('sarana umum berita acara can be created', function () {
    $sarana = SaranaUmum::factory()->create();

    $response = $this->actingAs($this->user)
        ->post(route('sarana-umum.berita-acara.store'), [
            'sarana_umum_id' => $sarana->id,
            'tanggal' => now()->toDateString(),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '10:00',
            'nama_guru' => 'Budi Santoso',
            'mata_pelajaran' => 'Informatika',
            'kelas' => 'X RPL 1',
            'jumlah_peserta' => 32,
            'kegiatan' => 'Praktik presentasi dengan proyektor.',
            'status' => 'final',
        ]);

    $response->assertRedirect(route('sarana-umum.berita-acara.index'));

    $this->assertDatabaseHas('sarana_umum_berita_acaras', [
        'sarana_umum_id' => $sarana->id,
        'nama_guru' => 'Budi Santoso',
        'kelas' => 'X RPL 1',
        'status' => 'final',
    ]);
});

test('sarana umum csv import preview and process works', function () {
    Storage::fake('local');

    $file = UploadedFile::fake()->createWithContent('sarana.csv', implode(PHP_EOL, [
        'kode_inventaris,nama,jenis,lokasi,kondisi,status',
        'SRN-100,AC Lobby,AC,Lobby,baik,aktif',
        'SRN-101,Proyektor Aula,Proyektor,Aula,rusak_ringan,dalam_perbaikan',
    ]));

    $this->actingAs($this->user)
        ->post(route('sarana-umum.data-sarana.import.preview'), [
            'file' => $file,
        ])
        ->assertSuccessful();

    $this->actingAs($this->user)
        ->post(route('sarana-umum.data-sarana.import.process'))
        ->assertRedirect(route('sarana-umum.data-sarana.index'));

    $this->assertDatabaseHas('sarana_umums', [
        'kode_inventaris' => 'SRN-100',
        'nama' => 'AC Lobby',
    ]);
});

test('staff can not create data sarana', function () {
    $staffUser = User::factory()->create(['role' => 'staff']);

    $this->actingAs($staffUser)
        ->post(route('sarana-umum.data-sarana.store'), [
            'kode_inventaris' => 'SRN-777',
            'nama' => 'CCTV Utama',
            'jenis' => 'CCTV',
            'lokasi' => 'Koridor',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ])
        ->assertForbidden();
});

test('staff can create maintenance log', function () {
    $staffUser = User::factory()->create(['role' => 'staff']);
    $sarana = SaranaUmum::factory()->create([
        'kondisi' => 'baik',
        'status' => 'aktif',
    ]);

    $this->actingAs($staffUser)
        ->post(route('sarana-umum.maintenance-log.store'), [
            'sarana_umum_id' => $sarana->id,
            'tanggal_lapor' => now()->toDateString(),
            'keluhan' => 'AC tidak dingin.',
            'status' => 'pending',
            'kondisi_sebelum' => 'rusak_ringan',
        ])
        ->assertRedirect(route('sarana-umum.maintenance-log.index'));
});

test('preventive maintenance completion updates next schedule', function () {
    $preventive = SaranaUmumPreventiveMaintenance::factory()->create([
        'interval_hari' => 30,
        'tanggal_maintenance_berikutnya' => now()->toDateString(),
    ]);

    $this->actingAs($this->user)
        ->patch(route('sarana-umum.preventive-maintenance.complete', $preventive), [
            'tanggal_selesai' => now()->toDateString(),
        ])
        ->assertRedirect(route('sarana-umum.preventive-maintenance.index'));

    $preventive->refresh();
    expect($preventive->tanggal_maintenance_terakhir?->toDateString())->toBe(now()->toDateString());
    expect($preventive->tanggal_maintenance_berikutnya?->toDateString())->toBe(now()->addDays(30)->toDateString());
});
