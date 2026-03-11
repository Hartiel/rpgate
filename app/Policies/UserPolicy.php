<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;

class UserPolicy
{
    /**
     * Admin can view any user.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Admin can update user or user can update itself.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role === UserRole::ADMIN;
    }

    /**
     * Admin can delete other users.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === UserRole::ADMIN && $user->id !== $model->id;
    }
}
