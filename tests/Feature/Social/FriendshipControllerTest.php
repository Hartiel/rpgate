<?php

declare(strict_types=1);

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('guests are unauthorized to access friendship endpoints', function () {
    $this->getJson('/api/friends')->assertStatus(401);
    $this->getJson('/api/friends/pending')->assertStatus(401);
    $this->postJson('/api/friends/request', ['friend_id' => '123'])->assertStatus(401);
});

test('user can send a friend request via API', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    Sanctum::actingAs($sender);

    $response = $this->postJson('/api/friends/request', [
        'friend_id' => $receiver->id,
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'data' => ['id', 'user_id', 'friend_id', 'status', 'action_user_id'],
    ]);

    $this->assertDatabaseHas('friendships', [
        'user_id' => min($sender->id, $receiver->id),
        'friend_id' => max($sender->id, $receiver->id),
        'status' => 'pending',
        'action_user_id' => $sender->id,
    ]);
});

test('user cannot send request to non-existent user', function () {
    $sender = User::factory()->create();
    Sanctum::actingAs($sender);

    $response = $this->postJson('/api/friends/request', [
        'friend_id' => 'non-existent-ulid',
    ]);

    $response->assertStatus(422);
});

test('user can accept a pending request via API', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    // Create a pending request
    $ids = [$sender->id, $receiver->id];
    sort($ids);
    Friendship::create([
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
        'action_user_id' => $sender->id,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($receiver);

    $response = $this->patchJson("/api/friends/{$sender->id}/accept");

    $response->assertStatus(200);
    $this->assertDatabaseHas('friendships', [
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
        'status' => 'accepted',
        'action_user_id' => $receiver->id,
    ]);
});

test('user can decline a pending request via API', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $ids = [$sender->id, $receiver->id];
    sort($ids);
    Friendship::create([
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
        'action_user_id' => $sender->id,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($receiver);

    $response = $this->deleteJson("/api/friends/{$sender->id}/decline");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('friendships', [
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
    ]);
});

test('user can unfriend via API', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $ids = [$user->id, $friend->id];
    sort($ids);
    Friendship::create([
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
        'action_user_id' => $user->id,
        'status' => 'accepted',
    ]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson("/api/friends/{$friend->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('friendships', [
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
    ]);
});

test('user can block user via API', function () {
    $blocker = User::factory()->create();
    $blocked = User::factory()->create();

    Sanctum::actingAs($blocker);

    $response = $this->postJson("/api/friends/{$blocked->id}/block");

    $response->assertSuccessful();
    $this->assertDatabaseHas('friendships', [
        'status' => 'blocked',
        'action_user_id' => $blocker->id,
    ]);
});

test('user can unblock user via API', function () {
    $blocker = User::factory()->create();
    $blocked = User::factory()->create();

    $ids = [$blocker->id, $blocked->id];
    sort($ids);
    Friendship::create([
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
        'action_user_id' => $blocker->id,
        'status' => 'blocked',
    ]);

    Sanctum::actingAs($blocker);

    $response = $this->deleteJson("/api/friends/{$blocked->id}/unblock");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('friendships', [
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
    ]);
});

test('user can list friends via API', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $ids = [$user->id, $friend->id];
    sort($ids);
    Friendship::create([
        'user_id' => $ids[0],
        'friend_id' => $ids[1],
        'action_user_id' => $user->id,
        'status' => 'accepted',
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/friends');

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $friend->id);
});

test('user can view pending invitations via API', function () {
    $user = User::factory()->create();
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    // Outgoing pending request
    $idsOut = [$user->id, $receiver->id];
    sort($idsOut);
    Friendship::create([
        'user_id' => $idsOut[0],
        'friend_id' => $idsOut[1],
        'action_user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Incoming pending request
    $idsIn = [$user->id, $sender->id];
    sort($idsIn);
    Friendship::create([
        'user_id' => $idsIn[0],
        'friend_id' => $idsIn[1],
        'action_user_id' => $sender->id,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/friends/pending');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'received' => [],
            'sent' => [],
        ],
    ]);
    $response->assertJsonCount(1, 'data.received');
    $response->assertJsonCount(1, 'data.sent');
});
