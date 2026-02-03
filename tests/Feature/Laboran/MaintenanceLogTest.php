<?php

use App\Models\KategoriPerangkat;
use App\Models\KomponenPerangkat;
use App\Models\Laboratorium;
use App\Models\MaintenanceLog;
use App\Models\UnitKomputer;
use App\Models\User;

beforeEach(function () {
    $this->laboran = User::factory()->create(['role' => 'laboran']);
    $this->laboratorium = Laboratorium::factory()->create();
    $this->unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);
    $this->kategori = KategoriPerangkat::factory()->create();
    $this->komponen = KomponenPerangkat::factory()->create([
        'unit_komputer_id' => $this->unit->id,
        'kategori_id' => $this->kategori->id,
    ]);
});

test('maintenance log index can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.maintenance-log.index'));

    $response->assertStatus(200);
});

test('maintenance log create can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.maintenance-log.create'));

    $response->assertStatus(200);
});

test('maintenance log can be created', function () {
    $response = $this->actingAs($this->laboran)
        ->post(route('laboran.maintenance-log.store'), [
            'komponen_perangkat_id' => $this->komponen->id,
            'tanggal_lapor' => now()->toDateString(),
            'keluhan' => 'Monitor tidak menyala',
            'status' => 'pending',
            'kondisi_sebelum' => 'rusak_berat',
        ]);

    $response->assertRedirect(route('laboran.maintenance-log.index'));
    $this->assertDatabaseHas('maintenance_logs', [
        'komponen_perangkat_id' => $this->komponen->id,
        'keluhan' => 'Monitor tidak menyala',
    ]);
});

test('maintenance log show can be rendered', function () {
    $log = MaintenanceLog::factory()->create([
        'komponen_perangkat_id' => $this->komponen->id,
        'pelapor_id' => $this->laboran->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.maintenance-log.show', $log));

    $response->assertStatus(200);
});

test('maintenance log edit can be rendered', function () {
    $log = MaintenanceLog::factory()->create([
        'komponen_perangkat_id' => $this->komponen->id,
        'pelapor_id' => $this->laboran->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.maintenance-log.edit', $log));

    $response->assertStatus(200);
});

test('maintenance log can be updated', function () {
    $log = MaintenanceLog::factory()->create([
        'komponen_perangkat_id' => $this->komponen->id,
        'pelapor_id' => $this->laboran->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->laboran)
        ->put(route('laboran.maintenance-log.update', $log), [
            'komponen_perangkat_id' => $this->komponen->id,
            'tanggal_lapor' => now()->toDateString(),
            'keluhan' => 'Sudah diperbaiki',
            'status' => 'selesai',
            'kondisi_sebelum' => 'rusak_berat',
            'kondisi_sesudah' => 'baik',
            'tanggal_selesai' => now()->toDateString(),
        ]);

    $response->assertRedirect(route('laboran.maintenance-log.index'));
    $this->assertDatabaseHas('maintenance_logs', [
        'id' => $log->id,
        'status' => 'selesai',
    ]);
});

test('maintenance log can be deleted', function () {
    $log = MaintenanceLog::factory()->create([
        'komponen_perangkat_id' => $this->komponen->id,
        'pelapor_id' => $this->laboran->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->delete(route('laboran.maintenance-log.destroy', $log));

    $response->assertRedirect(route('laboran.maintenance-log.index'));
    $this->assertDatabaseMissing('maintenance_logs', [
        'id' => $log->id,
    ]);
});
