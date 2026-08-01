<?php

namespace App\Actions\Social;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SendFriendRequestAction
{
    /**
     * Execute action to send a friend request.
     */
    public function execute(User $sender, User $receiver): Friendship
    {
        // User not self-invite
        if ($sender->id === $receiver->id) {
            throw ValidationException::withMessages([
                'friend_id' => ['You cannot send a friend request to yourself.'],
            ]);
        }

        $ids = [$sender->id, $receiver->id];
        sort($ids);

        $leftUserId = $ids[0]; // Left always the smaller ULID string
        $rightUserId = $ids[1]; // Right always the larger ULID string

        // 3. Verify if there is already any history between these two users
        $existingFriendship = Friendship::where('user_id', $leftUserId)
            ->where('friend_id', $rightUserId)
            ->first();

        if ($existingFriendship) {
            // If they are already friends or there is a pending invite, block the operation
            $message = match ($existingFriendship->status) {
                'accepted' => 'You are already friends with this user.',
                'pending' => 'There is already a pending invite between you.',
                'blocked' => 'Unable to send the invite.', // Abstraction for privacy/blocking
                default => 'A request has already been processed previously.',
            };

            throw ValidationException::withMessages([
                'friend_id' => [$message],
            ]);
        }

        // 4. If passed all the checks, create the clean record in our Pivot
        return Friendship::create([
            'user_id' => $leftUserId,
            'friend_id' => $rightUserId,
            'action_user_id' => $sender->id, // Who is taking the initiative
            'status' => 'pending',
        ]);
    }
}
