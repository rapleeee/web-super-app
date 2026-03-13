<?php

use App\Models\Laboratorium;
use App\Models\UnitKomputer;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->laboran = User::factory()->create(['role' => 'laboran']);
    $this->laboratorium = Laboratorium::factory()->create();
});

test('unit komputer index can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.index'));

    $response->assertSuccessful();
});

test('unit komputer index applies per page selection', function () {
    UnitKomputer::factory()->count(12)->create([
        'laboratorium_id' => $this->laboratorium->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.index', [
            'per_page' => 5,
        ]));

    $response
        ->assertSuccessful()
        ->assertViewHas('unitKomputers', function ($unitKomputers): bool {
            return $unitKomputers->perPage() === 5
                && $unitKomputers->count() === 5;
        });
});

test('unit komputer create can be rendered', function () {
    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.create'));

    $response->assertSuccessful();
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

    $response->assertSuccessful();
});

test('unit komputer edit can be rendered', function () {
    $unit = UnitKomputer::factory()->create(['laboratorium_id' => $this->laboratorium->id]);

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.edit', $unit));

    $response->assertSuccessful();
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

test('selected unit komputer can be bulk deleted', function () {
    $units = UnitKomputer::factory()->count(3)->create([
        'laboratorium_id' => $this->laboratorium->id,
    ]);

    $response = $this->actingAs($this->laboran)
        ->delete(route('laboran.unit-komputer.bulk-delete'), [
            'unit_ids' => [$units[0]->id, $units[1]->id],
        ]);

    $response->assertSessionHas('success', '2 unit komputer berhasil dihapus.');

    $this->assertDatabaseMissing('unit_komputers', [
        'id' => $units[0]->id,
    ]);
    $this->assertDatabaseMissing('unit_komputers', [
        'id' => $units[1]->id,
    ]);
    $this->assertDatabaseHas('unit_komputers', [
        'id' => $units[2]->id,
    ]);
});

test('selected unit komputer can be bulk updated with overwrite mode', function () {
    $units = UnitKomputer::factory()->count(2)->create([
        'laboratorium_id' => $this->laboratorium->id,
        'kondisi' => 'baik',
        'status' => 'aktif',
    ]);

    $this->actingAs($this->laboran)
        ->patch(route('laboran.unit-komputer.bulk-update'), [
            'unit_ids' => [$units[0]->id, $units[1]->id],
            'mode' => 'overwrite',
            'kondisi' => 'rusak_berat',
            'status' => 'tidak_aktif',
        ])
        ->assertSessionHas('success', '2 unit komputer berhasil diperbarui.');

    $this->assertDatabaseHas('unit_komputers', [
        'id' => $units[0]->id,
        'kondisi' => 'rusak_berat',
        'status' => 'tidak_aktif',
    ]);
    $this->assertDatabaseHas('unit_komputers', [
        'id' => $units[1]->id,
        'kondisi' => 'rusak_berat',
        'status' => 'tidak_aktif',
    ]);
});

test('selected unit komputer can be bulk updated with fill empty mode', function () {
    $emptyFieldsUnit = UnitKomputer::factory()->create([
        'laboratorium_id' => $this->laboratorium->id,
        'nomor_meja' => null,
        'keterangan' => null,
    ]);
    $filledFieldsUnit = UnitKomputer::factory()->create([
        'laboratorium_id' => $this->laboratorium->id,
        'nomor_meja' => 11,
        'keterangan' => 'Sudah ada keterangan',
    ]);

    $this->actingAs($this->laboran)
        ->patch(route('laboran.unit-komputer.bulk-update'), [
            'unit_ids' => [$emptyFieldsUnit->id, $filledFieldsUnit->id],
            'mode' => 'fill_empty',
            'nomor_meja' => 20,
            'keterangan' => 'Diisi lewat bulk',
        ])
        ->assertSessionHas('success', '1 unit komputer berhasil diisi field kosongnya.');

    $this->assertDatabaseHas('unit_komputers', [
        'id' => $emptyFieldsUnit->id,
        'nomor_meja' => 20,
        'keterangan' => 'Diisi lewat bulk',
    ]);
    $this->assertDatabaseHas('unit_komputers', [
        'id' => $filledFieldsUnit->id,
        'nomor_meja' => 11,
        'keterangan' => 'Sudah ada keterangan',
    ]);
});

test('template csv can be downloaded from storage file', function () {
    Storage::fake('local');
    Storage::disk('local')->put('templates/template_unit_komputer.csv', "kode_unit,nama,laboratorium,kondisi,status\n");

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.template'));

    $response->assertSuccessful();
    $response->assertDownload('template_unit_komputer.csv');
});

test('template csv can still be downloaded when storage file is missing', function () {
    Storage::fake('local');

    $response = $this->actingAs($this->laboran)
        ->get(route('laboran.unit-komputer.template'));

    $response->assertSuccessful();
    $response->assertDownload('template_unit_komputer.csv');
});

test('unit komputer import preview and process works when default disk is public', function () {
    config()->set('filesystems.default', 'public');

    $file = UploadedFile::fake()->createWithContent('unit-komputer.csv', implode(PHP_EOL, [
        'kode_unit,nama,laboratorium,nomor_meja,kondisi,status,keterangan',
        "PC-900,Komputer Import,{$this->laboratorium->nama},9,baik,aktif,Data import test",
        "PC-901,Komputer Import 2,{$this->laboratorium->nama},10,rusak_ringan,dalam_perbaikan,Data import test 2",
    ]));

    $this->actingAs($this->laboran);

    $previewResponse = $this
        ->post(route('laboran.unit-komputer.import.preview'), [
            'file' => $file,
        ]);

    $previewResponse
        ->assertSuccessful()
        ->assertSessionHas('unit_komputer.import_file.path');

    $storedPath = session('unit_komputer.import_file.path');
    expect($storedPath)->toBeString();
    expect(Storage::disk('local')->exists($storedPath))->toBeTrue();

    $response = $this->withSession([
        'unit_komputer' => [
            'import_file' => [
                'disk' => 'local',
                'path' => $storedPath,
            ],
        ],
        'import_file_path' => $storedPath,
    ])->post(route('laboran.unit-komputer.import.process'));

    $response
        ->assertRedirect(route('laboran.unit-komputer.index'))
        ->assertSessionHas('success', 'Data unit komputer berhasil diimport.');

    $this->assertDatabaseHas('unit_komputers', [
        'kode_unit' => 'PC-900',
        'nama' => 'Komputer Import',
    ]);

    $this->assertDatabaseHas('unit_komputers', [
        'kode_unit' => 'PC-901',
        'nama' => 'Komputer Import 2',
    ]);
});
