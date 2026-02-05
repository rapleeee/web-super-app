<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('backup index page can be rendered', function () {
    $response = $this->actingAs($this->user)->get(route('laboran.backup.index'));

    $response->assertStatus(200);
    $response->assertSee('Backup Database');
});

test('backup download redirects with error for non-mysql database', function () {
    // In testing we use SQLite, so this should redirect with error
    $response = $this->actingAs($this->user)->get(route('laboran.backup.download'));

    $response->assertRedirect(route('laboran.backup.index'));
    $response->assertSessionHas('error');
});

test('backup store redirects with error for non-mysql database', function () {
    // In testing we use SQLite, so this should redirect with error
    $response = $this->actingAs($this->user)->post(route('laboran.backup.store'));

    $response->assertRedirect(route('laboran.backup.index'));
    $response->assertSessionHas('error');
});

test('stored backup file can be downloaded', function () {
    // Create a test backup file
    $backupPath = storage_path('app/backups');
    if (! is_dir($backupPath)) {
        mkdir($backupPath, 0755, true);
    }

    $filename = 'test_backup.sql';
    file_put_contents($backupPath.'/'.$filename, '-- Test backup');

    $response = $this->actingAs($this->user)->get(route('laboran.backup.download-file', $filename));

    $response->assertStatus(200);
    $response->assertDownload($filename);

    // Cleanup
    unlink($backupPath.'/'.$filename);
});

test('stored backup file can be deleted', function () {
    // Create a test backup file
    $backupPath = storage_path('app/backups');
    if (! is_dir($backupPath)) {
        mkdir($backupPath, 0755, true);
    }

    $filename = 'test_backup_delete.sql';
    file_put_contents($backupPath.'/'.$filename, '-- Test backup');

    expect(file_exists($backupPath.'/'.$filename))->toBeTrue();

    $response = $this->actingAs($this->user)->delete(route('laboran.backup.destroy', $filename));

    $response->assertRedirect(route('laboran.backup.index'));
    $response->assertSessionHas('success');
    expect(file_exists($backupPath.'/'.$filename))->toBeFalse();
});

test('downloading nonexistent backup file returns error', function () {
    $response = $this->actingAs($this->user)->get(route('laboran.backup.download-file', 'nonexistent.sql'));

    $response->assertRedirect(route('laboran.backup.index'));
    $response->assertSessionHas('error');
});

test('deleting nonexistent backup file returns error', function () {
    $response = $this->actingAs($this->user)->delete(route('laboran.backup.destroy', 'nonexistent.sql'));

    $response->assertRedirect(route('laboran.backup.index'));
    $response->assertSessionHas('error');
});
