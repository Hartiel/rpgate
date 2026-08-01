<?php

declare(strict_types=1);

use App\Actions\Social\SendFriendRequestAction;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can send a friend request sorting ULIDs correctly', function () {
    // Arrange: Create two valid users and find the lexicographical order of their IDs
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $expectedLeftUserId = $sender->id < $receiver->id ? $sender->id : $receiver->id;
    $expectedRightUserId = $sender->id > $receiver->id ? $sender->id : $receiver->id;

    $action = app(SendFriendRequestAction::class);

    // Act: Execute the action to create the friend request
    $friendship = $action->execute($sender, $receiver);

    // Assert: Verify the pivot record state, its fields and database persistence
    expect($friendship)->toBeInstanceOf(Friendship::class);

    $this->assertEquals($expectedLeftUserId, $friendship->user_id);
    $this->assertEquals($expectedRightUserId, $friendship->friend_id);
    $this->assertEquals($sender->id, $friendship->action_user_id);
    $this->assertEquals('pending', $friendship->status);

    $this->assertDatabaseHas('friendships', [
        'id' => $friendship->id,
        'user_id' => $expectedLeftUserId,
        'friend_id' => $expectedRightUserId,
        'action_user_id' => $sender->id,
        'status' => 'pending',
    ]);
});

test('user cannot send a friend request to themselves', function () {
    // Arrange: Create a single user instance
    $user = User::factory()->create();
    $action = app(SendFriendRequestAction::class);

    // Act & Assert: Expect a validation exception when user targets themselves
    expect(fn () => $action->execute($user, $user))
        ->toThrow(ValidationException::class);
});

test('user cannot send a friend request if a pending connection already exists', function () {
    // Arrange: Create two users and initialize a pending request between them
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $action = app(SendFriendRequestAction::class);

    $action->execute($sender, $receiver);

    // Act & Assert: Verify that both identical and inverted requests throw validation exceptions
    expect(fn () => $action->execute($sender, $receiver))
        ->toThrow(ValidationException::class);

    expect(fn () => $action->execute($receiver, $sender))
        ->toThrow(ValidationException::class);
});
