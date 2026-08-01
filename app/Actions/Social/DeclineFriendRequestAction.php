<?php

declare(strict_types=1);

namespace App\Actions\Social;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class DeclineFriendRequestAction
{
    /**
     * Execute the business logic to decline a pending friend request.
     *
     * @throws ValidationException
     */
    public function execute(User $user, User $sender): void
    {
        $ids = [$user->id, $sender->id];
        sort($ids);

        $leftUserId = $ids[0];
        $rightUserId = $ids[1];

        $friendship = Friendship::where('user_id', $leftUserId)
            ->where('friend_id', $rightUserId)
            ->first();

        if (! $friendship || $friendship->status !== 'pending') {
            throw ValidationException::withMessages([
                'friendship' => ['There is no pending friend request between these users.'],
            ]);
        }

        // Ensure the recipient is the one declining the request
        if ($friendship->action_user_id === $user->id) {
            throw ValidationException::withMessages([
                'friendship' => ['You cannot decline a friend request that you sent yourself.'],
            ]);
        }

        $friendship->delete();
    }
}
