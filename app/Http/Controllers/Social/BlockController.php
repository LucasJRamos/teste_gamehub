<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SocialGraphService;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function __construct(
        protected SocialGraphService $socialGraphService,
    ) {
    }

    public function store(Request $request, User $user)
    {
        $this->socialGraphService->block($request->user(), $user);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Usuario bloqueado com sucesso.',
                'data' => UserResource::make($user->loadCount(['followers', 'following', 'portfolioItems'])),
            ], 201);
        }

        return to_route('users.index')->with('success', "{$user->username} foi bloqueado.");
    }

    public function destroy(Request $request, User $user)
    {
        $this->socialGraphService->unblock($request->user(), $user);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Usuario desbloqueado com sucesso.',
                'data' => UserResource::make($user->loadCount(['followers', 'following', 'portfolioItems'])),
            ]);
        }

        return back()->with('success', "{$user->username} foi desbloqueado.");
    }
}
