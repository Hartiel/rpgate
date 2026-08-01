<?php

declare(strict_types=1);

namespace App\Actions\Social;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AcceptFriendRequestAction
{
    /**
     * Execute the business logic to accept a pending friend request.
     *
     * @throws ValidationException
     */
    public function execute(User $user, User $sender): Friendship
    {
        // Sort the ULIDs the asymmetric table layout
        $ids = [$user->id, $sender->id];
        sort($ids);

        $leftUserId = $ids[0];
        $rightUserId = $ids[1];

        // Fetch the current connection state from the database
        $friendship = Friendship::where('user_id', $leftUserId)
            ->where('friend_id', $rightUserId)
            ->first();

        // Safeguard against missing rows or non-pending requests
        if (! $friendship || $friendship->status !== 'pending') {
            throw ValidationException::withMessages([
                'friendship' => ['There is no pending friend request between these users.'],
            ]);
        }

        // Ensure the recipient is the one accepting the incoming request
        if ($friendship->action_user_id === $user->id) {
            throw ValidationException::withMessages([
                'friendship' => ['You cannot accept a friend request that you sent yourself.'],
            ]);
        }

        // Elevate connection state to accepted and mark active actor ID
        $friendship->update([
            'status' => 'accepted',
            'action_user_id' => $user->id,
        ]);

        return $friendship;
    }
}
