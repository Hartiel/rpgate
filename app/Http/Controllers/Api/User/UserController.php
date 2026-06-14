<?php

namespace App\Http\Controllers\Api\User;

use App\Actions\User\GetUserAction;
use App\Actions\User\UpdateUserSettingsAction;
use App\DTOs\Api\User\UpdateUserSettingsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserSettingsRequest;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\Request;

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
}
