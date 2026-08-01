<?php

declare(strict_types=1);

use App\Actions\Social\AcceptFriendRequestAction;
use App\Actions\Social\SendFriendRequestAction;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can accept a pending friend request', function () {
    // Arrange: Create two users and initialize a pending request
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $sendAction = app(SendFriendRequestAction::class);
    $acceptAction = app(AcceptFriendRequestAction::class);

    $initialFriendship = $sendAction->execute($sender, $receiver);

    // Act: Receiver accepts the incoming friend request
    $friendship = $acceptAction->execute($receiver, $sender);

    // Assert: Verify the status has updated to accepted and data is persistent
    expect($friendship)->toBeInstanceOf(Friendship::class);
    $this->assertEquals($initialFriendship->id, $friendship->id);
    $this->assertEquals('accepted', $friendship->status);
    $this->assertEquals($receiver->id, $friendship->action_user_id);

    $this->assertDatabaseHas('friendships', [
        'id' => $friendship->id,
        'status' => 'accepted',
        'action_user_id' => $receiver->id,
    ]);
});

test('user cannot accept a friend request that they sent themselves', function () {
    // Arrange: Create two users and send a request from sender to receiver
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $sendAction = app(SendFriendRequestAction::class);
    $acceptAction = app(AcceptFriendRequestAction::class);

    $sendAction->execute($sender, $receiver);

    // Act & Assert: Expect validation exception if the sender tries to accept their own request
    expect(fn () => $acceptAction->execute($sender, $receiver))
        ->toThrow(ValidationException::class);
});

test('user cannot accept a non existent or already processed friend request', function () {
    // Arrange: Create two users with no active connection history
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $acceptAction = app(AcceptFriendRequestAction::class);

    // Act & Assert: Fail immediately since no 'pending' row exists
    expect(fn () => $acceptAction->execute($receiver, $sender))
        ->toThrow(ValidationException::class);
});
