<?php

declare(strict_types=1);

namespace App\Actions\Social;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UnfriendAction
{
    /**
     * Execute the business logic to remove a friend.
     *
     * @throws ValidationException
     */
    public function execute(User $user, User $friend): void
    {
        $ids = [$user->id, $friend->id];
        sort($ids);

        $leftUserId = $ids[0];
        $rightUserId = $ids[1];

        $friendship = Friendship::where('user_id', $leftUserId)
            ->where('friend_id', $rightUserId)
            ->first();

        if (! $friendship || $friendship->status !== 'accepted') {
            throw ValidationException::withMessages([
                'friendship' => ['You are not friends with this user.'],
            ]);
        }

        $friendship->delete();
    }
}
