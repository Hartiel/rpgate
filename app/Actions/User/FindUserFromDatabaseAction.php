<?php

namespace App\Actions\User;

use App\Models\User;

class FindUserFromDatabaseAction
{
    public function execute(string $userId): User
    {
        return User::findOrFail($userId);
    }
}