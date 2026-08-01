<?php

declare(strict_types=1);

use App\Actions\Social\BlockUserAction;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can block another user', function () {
    $blocker = User::factory()->create();
    $blocked = User::factory()->create();

    $action = app(BlockUserAction::class);

    $friendship = $action->execute($blocker, $blocked);

    expect($friendship->status)->toBe('blocked');
    expect($friendship->action_user_id)->toBe($blocker->id);

    $this->assertDatabaseHas('friendships', [
        'user_id' => min($blocker->id, $blocked->id),
        'friend_id' => max($blocker->id, $blocked->id),
        'status' => 'blocked',
        'action_user_id' => $blocker->id,
    ]);
});

test('user cannot block themselves', function () {
    $user = User::factory()->create();
    $action = app(BlockUserAction::class);

    expect(fn () => $action->execute($user, $user))
        ->toThrow(ValidationException::class);
});
