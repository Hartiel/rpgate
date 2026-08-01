<?php

namespace App\Http\Controllers\Api\User;

use App\Actions\User\GetUserAction;
use App\Actions\User\UpdateUserSettingsAction;
use App\DTOs\Api\User\UpdateUserSettingsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserSettingsRequest;
use App\Http\Resources\Api\Social\UserFriendResource;
use App\Http\Resources\Api\User\UserResource;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Get the authenticated user.
     */
    public function getUser(Request $request, GetUserAction $action): UserResource
    {
        $userId = $request->user()->id;
        $user = $action->execute($userId);

        return new UserResource($user);
    }

    /**
     * Update strictly the user settings.
     */
    public function updateUserSettings(UpdateUserSettingsRequest $request, UpdateUserSettingsAction $action): UserResource
    {
        $userId = $request->user()->id;
        $dto = UpdateUserSettingsDTO::fromRequest($request);

        // 2. Executa a atualização no banco e limpa o Redis por dentro da Action
        $user = $action->execute($userId, $dto);

        // 3. Retorna o MESMO UserResource com o JSON atualizado para o Vue 3
        return new UserResource($user);
    }

    /**
     * Search users by name or username (excluding authenticated user).
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $search = $request->query('search');

        if (empty($search)) {
            return UserFriendResource::collection(collect());
        }

        $users = User::where('id', '!=', $request->user()->id)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        // Load relationship for each searched user to display current status
        $authUserId = $request->user()->id;
        foreach ($users as $user) {
            $ids = [$authUserId, $user->id];
            sort($ids);

            $friendship = Friendship::where('user_id', $ids[0])
                ->where('friend_id', $ids[1])
                ->first();

            if ($friendship) {
                $user->setRelation('pivot', $friendship);
            }
        }

        return UserFriendResource::collection($users);
    }

    /**
     * Show user details with friendship status.
     */
    public function show(User $user, Request $request): UserFriendResource
    {
        $authUserId = $request->user()->id;

        if ($authUserId !== $user->id) {
            $ids = [$authUserId, $user->id];
            sort($ids);

            $friendship = Friendship::where('user_id', $ids[0])
                ->where('friend_id', $ids[1])
                ->first();

            if ($friendship) {
                $user->setRelation('pivot', $friendship);
            }
        }

        return new UserFriendResource($user);
    }
}
