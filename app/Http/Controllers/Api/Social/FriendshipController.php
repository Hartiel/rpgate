<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Social;

use App\Actions\Social\AcceptFriendRequestAction;
use App\Actions\Social\BlockUserAction;
use App\Actions\Social\DeclineFriendRequestAction;
use App\Actions\Social\SendFriendRequestAction;
use App\Actions\Social\UnblockUserAction;
use App\Actions\Social\UnfriendAction;
use App\DTOs\Api\Social\FriendRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Social\BlockUserRequest;
use App\Http\Requests\Api\Social\FriendRequestRequest;
use App\Http\Resources\Api\Social\FriendshipResource;
use App\Http\Resources\Api\Social\UserFriendResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FriendshipController extends Controller
{
    /**
     * Get the list of accepted friends.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $friends = $request->user()->friends;

        return UserFriendResource::collection($friends);
    }

    /**
     * Get pending sent and received friend requests.
     */
    public function pending(Request $request): JsonResponse
    {
        $received = $request->user()->pending_requests;
        $sent = $request->user()->sent_requests;

        return response()->json([
            'data' => [
                'received' => UserFriendResource::collection($received),
                'sent' => UserFriendResource::collection($sent),
            ]
        ]);
    }

    /**
     * Send a friend request to another user.
     */
    public function sendRequest(FriendRequestRequest $request, SendFriendRequestAction $action): JsonResponse
    {
        $dto = FriendRequestDTO::fromRequest($request);
        $receiver = User::findOrFail($dto->friendId);

        $friendship = $action->execute($request->user(), $receiver);

        return (new FriendshipResource($friendship))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Accept a pending friend request from another user.
     */
    public function acceptRequest(User $user, Request $request, AcceptFriendRequestAction $action): FriendshipResource
    {
        $friendship = $action->execute($request->user(), $user);

        return new FriendshipResource($friendship);
    }

    /**
     * Decline a pending friend request from another user.
     */
    public function declineRequest(User $user, Request $request, DeclineFriendRequestAction $action): JsonResponse
    {
        $action->execute($request->user(), $user);

        return response()->json(null, 204);
    }

    /**
     * Unfriend an existing friend.
     */
    public function unfriend(User $user, Request $request, UnfriendAction $action): JsonResponse
    {
        $action->execute($request->user(), $user);

        return response()->json(null, 204);
    }

    /**
     * Block a user.
     */
    public function blockUser(User $user, BlockUserRequest $request, BlockUserAction $action): FriendshipResource
    {
        $friendship = $action->execute($request->user(), $user);

        return new FriendshipResource($friendship);
    }

    /**
     * Unblock a user.
     */
    public function unblockUser(User $user, Request $request, UnblockUserAction $action): JsonResponse
    {
        $action->execute($request->user(), $user);

        return response()->json(null, 204);
    }
}
