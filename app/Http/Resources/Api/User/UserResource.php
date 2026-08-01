<?php

namespace App\Http\Resources\Api\User;

use App\Enums\ThemeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $settings = $this->settings ?? [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'discriminator' => sprintf('%04d', $this->discriminator),
            'email' => $this->email,
            'avatar' => 'https://i.pravatar.cc/150?u=' . $this->id,
            'settings' => [
                'theme' => isset($settings['theme'])
                    ? $settings['theme'] instanceof ThemeEnum
                    ? $settings['theme']->value : $settings['theme']
                    : ThemeEnum::LIGHT->value,
                'compact_mode' => (bool) ($settings['compact_mode'] ?? false),
            ],
            'created_at' => $this->created_at,
        ];
    }
}
