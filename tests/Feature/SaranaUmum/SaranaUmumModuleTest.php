<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('sarana umum dashboard can be rendered', function () {
    $this->actingAs($this->user)
        ->get(route('sarana-umum.dashboard'))
        ->assertSuccessful();
});

test('sarana umum master pages can be rendered', function (string $routeName) {
    $this->actingAs($this->user)
        ->get(route($routeName))
        ->assertSuccessful();
})->with([
    'data sarana index' => 'sarana-umum.data-sarana.index',
    'petugas sarpras index' => 'sarana-umum.petugas-sarpras.index',
    'data guru index' => 'sarana-umum.data-guru.index',
    'data ruangan index' => 'sarana-umum.data-ruangan.index',
    'kategori sarana index' => 'sarana-umum.kategori-sarana.index',
    'maintenance log index' => 'sarana-umum.maintenance-log.index',
    'preventive maintenance index' => 'sarana-umum.preventive-maintenance.index',
    'berita acara index' => 'sarana-umum.berita-acara.index',
    'audit log index' => 'sarana-umum.audit-log.index',
    'import sarana form' => 'sarana-umum.data-sarana.import',
    'backup index' => 'sarana-umum.backup.index',
]);
