<?php

declare(strict_types=1);

namespace App\DTOs\Api\Social;

use App\Http\Requests\Api\Social\FriendRequestRequest;

class FriendRequestDTO
{
    public function __construct(
        public readonly string $friendId,
    ) {}

    public static function fromRequest(FriendRequestRequest $request): self
    {
        return new self(
            friendId: $request->validated('friend_id'),
        );
    }
}
