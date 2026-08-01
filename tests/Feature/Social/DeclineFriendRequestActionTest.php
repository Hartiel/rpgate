<?php

declare(strict_types=1);

use App\Actions\Social\DeclineFriendRequestAction;
use App\Actions\Social\SendFriendRequestAction;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can decline a pending friend request', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $sendAction = app(SendFriendRequestAction::class);
    $declineAction = app(DeclineFriendRequestAction::class);

    $sendAction->execute($sender, $receiver);

    // Receiver declines the request
    $declineAction->execute($receiver, $sender);

    $this->assertDatabaseMissing('friendships', [
        'user_id' => min($sender->id, $receiver->id),
        'friend_id' => max($sender->id, $receiver->id),
    ]);
});

test('user cannot decline a friend request they sent themselves', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $sendAction = app(SendFriendRequestAction::class);
    $declineAction = app(DeclineFriendRequestAction::class);

    $sendAction->execute($sender, $receiver);

    expect(fn () => $declineAction->execute($sender, $receiver))
        ->toThrow(ValidationException::class);
});
