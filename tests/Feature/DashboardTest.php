<?php

use App\Http\Middleware\EnsureApplicationNotInCustomMaintenanceMode;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::forget(EnsureApplicationNotInCustomMaintenanceMode::cacheKey());
});

afterEach(function () {
    Cache::forget(EnsureApplicationNotInCustomMaintenanceMode::cacheKey());
});

test('dashboard sarana umum menu points to sarana umum page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
    $response->assertSee('Sarana Umum');
    $response->assertSee(route('sarana-umum.dashboard'), false);
});

test('dashboard kepegawaian tu menu points to tu page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
    $response->assertSee('Kepegawaian TU');
    $response->assertSee(route('kepegawaian-tu.izin-karyawan.index'), false);
});

test('admin can see maintenance toggle on dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
    $response->assertSee('Mode Maintenance');
    $response->assertSee('Aktifkan Maintenance');
});

test('test user can see maintenance toggle on dashboard', function () {
    $testUser = User::factory()->staff()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->actingAs($testUser)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
    $response->assertSee('Mode Maintenance');
});

test('staff can not see maintenance toggle on dashboard', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
    $response->assertDontSee('Mode Maintenance');
});

test('admin can enable maintenance mode from dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)
        ->patch(route('dashboard.maintenance-mode.update'), [
            'enabled' => 1,
        ]);

    $response->assertRedirect(route('dashboard.index'));
    $response->assertSessionHas('success', 'Mode maintenance aktif.');
    expect(Cache::get(EnsureApplicationNotInCustomMaintenanceMode::cacheKey()))->toBeTrue();
});

test('test user can disable maintenance mode from dashboard', function () {
    Cache::forever(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), true);

    $testUser = User::factory()->staff()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->actingAs($testUser)
        ->patch(route('dashboard.maintenance-mode.update'), [
            'enabled' => 0,
        ]);

    $response->assertRedirect(route('dashboard.index'));
    $response->assertSessionHas('success', 'Mode maintenance dinonaktifkan.');
    expect(Cache::has(EnsureApplicationNotInCustomMaintenanceMode::cacheKey()))->toBeFalse();
});

test('pejabat can not toggle maintenance mode from dashboard', function () {
    $pejabat = User::factory()->pejabat()->create();

    $this->actingAs($pejabat)
        ->patch(route('dashboard.maintenance-mode.update'), [
            'enabled' => 1,
        ])
        ->assertForbidden();
});

test('staff can not toggle maintenance mode from dashboard', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)
        ->patch(route('dashboard.maintenance-mode.update'), [
            'enabled' => 1,
        ])
        ->assertForbidden();
});

test('staff sees maintenance page when mode is active', function () {
    Cache::forever(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), true);

    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)
        ->get(route('dashboard.index'));

    $response->assertStatus(503);
    $response->assertSee('Sistem Sedang Dalam Pemeliharaan');
    $response->assertSee('Quote Hari Ini');
});

test('admin can still access dashboard when mode is active', function () {
    Cache::forever(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), true);

    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
});

test('test user can still access dashboard when mode is active', function () {
    Cache::forever(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), true);

    $testUser = User::factory()->staff()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->actingAs($testUser)
        ->get(route('dashboard.index'));

    $response->assertSuccessful();
});

test('login page remains accessible when mode is active', function () {
    Cache::forever(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), true);

    $response = $this->get(route('login'));

    $response->assertSuccessful();
});
