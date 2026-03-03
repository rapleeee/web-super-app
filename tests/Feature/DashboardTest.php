<?php

use App\Models\User;

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
