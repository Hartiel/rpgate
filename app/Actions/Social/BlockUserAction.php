<?php

declare(strict_types=1);

namespace App\Actions\Social;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class BlockUserAction
{
    /**
     * Execute the business logic to block a user.
     *
     * @throws ValidationException
     */
    public function execute(User $blocker, User $blocked): Friendship
    {
        if ($blocker->id === $blocked->id) {
            throw ValidationException::withMessages([
                'friend_id' => ['You cannot block yourself.'],
            ]);
        }

        $ids = [$blocker->id, $blocked->id];
        sort($ids);

        $leftUserId = $ids[0];
        $rightUserId = $ids[1];

        $friendship = Friendship::where('user_id', $leftUserId)
            ->where('friend_id', $rightUserId)
            ->first();

        if (! $friendship) {
            $friendship = new Friendship([
                'user_id' => $leftUserId,
                'friend_id' => $rightUserId,
            ]);
        }

        $friendship->status = 'blocked';
        $friendship->action_user_id = $blocker->id;
        $friendship->save();

        return $friendship;
    }
}
