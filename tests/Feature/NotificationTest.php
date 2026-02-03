<?php

use App\Models\Notification;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'laboran']);
});

test('can view notifications page', function () {
    $response = $this->actingAs($this->user)->get(route('laboran.notifications.index'));

    $response->assertStatus(200);
    $response->assertSee('Notifikasi');
});

test('can get recent notifications via ajax', function () {
    // Create some notifications
    Notification::create([
        'user_id' => $this->user->id,
        'type' => 'maintenance_created',
        'title' => 'Test Notification',
        'message' => 'This is a test',
        'icon' => 'bell',
    ]);

    $response = $this->actingAs($this->user)
        ->getJson(route('laboran.notifications.recent'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['notifications', 'unread_count']);
});

test('can get unread notifications count', function () {
    Notification::create([
        'user_id' => $this->user->id,
        'type' => 'test',
        'title' => 'Unread',
        'message' => 'Test',
        'icon' => 'bell',
    ]);

    $response = $this->actingAs($this->user)
        ->getJson(route('laboran.notifications.unread-count'));

    $response->assertStatus(200);
    $response->assertJson(['count' => 1]);
});

test('can mark all notifications as read', function () {
    Notification::create([
        'user_id' => $this->user->id,
        'type' => 'test',
        'title' => 'Unread 1',
        'message' => 'Test',
        'icon' => 'bell',
    ]);

    Notification::create([
        'user_id' => $this->user->id,
        'type' => 'test',
        'title' => 'Unread 2',
        'message' => 'Test',
        'icon' => 'bell',
    ]);

    $response = $this->actingAs($this->user)
        ->postJson(route('laboran.notifications.mark-all-read'));

    $response->assertStatus(200);

    expect(Notification::where('user_id', $this->user->id)->whereNull('read_at')->count())->toBe(0);
});

test('can mark single notification as read and redirect', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'test',
        'title' => 'Test',
        'message' => 'Test',
        'icon' => 'bell',
        'link' => route('laboran.index'),
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('laboran.notifications.mark-as-read', $notification));

    $response->assertRedirect(route('laboran.index'));
    expect($notification->fresh()->read_at)->not->toBeNull();
});

test('can delete notification', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'test',
        'title' => 'Test',
        'message' => 'Test',
        'icon' => 'bell',
    ]);

    $response = $this->actingAs($this->user)
        ->delete(route('laboran.notifications.destroy', $notification));

    $response->assertRedirect();
    expect(Notification::find($notification->id))->toBeNull();
});

test('cannot access other users notification', function () {
    $otherUser = User::factory()->create();
    $notification = Notification::create([
        'user_id' => $otherUser->id,
        'type' => 'test',
        'title' => 'Test',
        'message' => 'Test',
        'icon' => 'bell',
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('laboran.notifications.mark-as-read', $notification));

    $response->assertStatus(403);
});
