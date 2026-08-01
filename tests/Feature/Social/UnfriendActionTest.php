<?php

declare(strict_types=1);

use App\Actions\Social\AcceptFriendRequestAction;
use App\Actions\Social\SendFriendRequestAction;
use App\Actions\Social\UnfriendAction;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can remove an accepted friend', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $sendAction = app(SendFriendRequestAction::class);
    $acceptAction = app(AcceptFriendRequestAction::class);
    $unfriendAction = app(UnfriendAction::class);

    $sendAction->execute($sender, $receiver);
    $acceptAction->execute($receiver, $sender);

    // Unfriend
    $unfriendAction->execute($sender, $receiver);

    $this->assertDatabaseMissing('friendships', [
        'user_id' => min($sender->id, $receiver->id),
        'friend_id' => max($sender->id, $receiver->id),
    ]);
});

test('user cannot unfriend if they are not friends', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $unfriendAction = app(UnfriendAction::class);

    expect(fn () => $unfriendAction->execute($sender, $receiver))
        ->toThrow(ValidationException::class);
});
