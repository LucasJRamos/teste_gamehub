<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SocialGraphService;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct(
        protected SocialGraphService $socialGraphService,
    ) {
    }

    public function store(Request $request, User $user)
    {
        $this->socialGraphService->follow($request->user(), $user);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Usuario seguido com sucesso.',
                'data' => UserResource::make($user->loadCount(['followers', 'following', 'portfolioItems'])),
            ], 201);
        }

        return back()->with('success', "Agora voce esta seguindo {$user->username}.");
    }

    public function destroy(Request $request, User $user)
    {
        $this->socialGraphService->unfollow($request->user(), $user);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Usuario removido da sua lista de seguindo.',
                'data' => UserResource::make($user->loadCount(['followers', 'following', 'portfolioItems'])),
            ]);
        }

        return back()->with('success', "Voce deixou de seguir {$user->username}.");
    }
}
