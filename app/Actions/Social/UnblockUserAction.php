<?php

declare(strict_types=1);

namespace App\Actions\Social;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UnblockUserAction
{
    /**
     * Execute the business logic to unblock a user.
     *
     * @throws ValidationException
     */
    public function execute(User $blocker, User $blocked): void
    {
        $ids = [$blocker->id, $blocked->id];
        sort($ids);

        $leftUserId = $ids[0];
        $rightUserId = $ids[1];

        $friendship = Friendship::where('user_id', $leftUserId)
            ->where('friend_id', $rightUserId)
            ->first();

        if (! $friendship || $friendship->status !== 'blocked') {
            throw ValidationException::withMessages([
                'friendship' => ['This user is not blocked.'],
            ]);
        }

        // Validate that only the blocker who initiated the block can unblock
        if ($friendship->action_user_id !== $blocker->id) {
            throw ValidationException::withMessages([
                'friendship' => ['You cannot unblock a user that you did not block.'],
            ]);
        }

        $friendship->delete();
    }
}
