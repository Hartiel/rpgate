<?php

namespace App\Actions\User;

use App\DTOs\Api\User\UpdateUserSettingsDTO;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UpdateUserSettingsAction
{
    /**
     * Update user settings and invalidate current cache.
     */
    public function execute(string $userId, UpdateUserSettingsDTO $dto): User
    {
        $user = User::findOrFail($userId);

        $user->update([
            'settings' => [
                'theme' => $dto->theme->value,
                'compact_mode' => $dto->compactMode,
            ],
        ]);

        Cache::forget("user:{$userId}");

        return $user;
    }
}
