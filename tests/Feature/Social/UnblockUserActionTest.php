<?php

declare(strict_types=1);

use App\Actions\Social\BlockUserAction;
use App\Actions\Social\UnblockUserAction;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can unblock a user they blocked', function () {
    $blocker = User::factory()->create();
    $blocked = User::factory()->create();

    $blockAction = app(BlockUserAction::class);
    $unblockAction = app(UnblockUserAction::class);

    $blockAction->execute($blocker, $blocked);

    // Unblock
    $unblockAction->execute($blocker, $blocked);

    $this->assertDatabaseMissing('friendships', [
        'user_id' => min($blocker->id, $blocked->id),
        'friend_id' => max($blocker->id, $blocked->id),
    ]);
});

test('user cannot unblock a user they did not block', function () {
    $blocker = User::factory()->create();
    $blocked = User::factory()->create();
    $other = User::factory()->create();

    $blockAction = app(BlockUserAction::class);
    $unblockAction = app(UnblockUserAction::class);

    $blockAction->execute($blocker, $blocked);

    expect(fn () => $unblockAction->execute($other, $blocked))
        ->toThrow(ValidationException::class);
});
