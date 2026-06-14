<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class GetUserAction
{
    public function __construct(
        protected FindUserFromDatabaseAction $databaseAction
    ) {}

    /**
     * Call action for search de user (with Cache).
     */
    public function execute(string $userId): User
    {
        $attributes = Cache::remember("user:{$userId}", now()->addDay(), function () use ($userId) {
            $user = $this->databaseAction->execute($userId);

            return $user->getAttributes();
        });

        return (new User)->newFromBuilder($attributes);
    }
}
