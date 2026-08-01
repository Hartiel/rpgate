<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Social;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFriendResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'discriminator' => sprintf('%04d', $this->discriminator),
            'email' => $this->email,
            'avatar' => 'https://i.pravatar.cc/150?u=' . $this->id,
            'friendship' => $this->when($this->pivot !== null, function () {
                return [
                    'id' => $this->pivot->id,
                    'status' => $this->pivot->status,
                    'action_user_id' => $this->pivot->action_user_id,
                ];
            }),
        ];
    }
}
