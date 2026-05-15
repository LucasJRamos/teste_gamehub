<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserDirectoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected UserDirectoryService $userDirectoryService,
    ) {
    }

    public function __invoke(Request $request): Response|JsonResponse
    {
        $currentUser = UserResource::make(
            $request->user()->loadCount(['followers', 'following', 'portfolioItems'])
        )->resolve($request);
        $suggestions = UserResource::collection(
            $this->userDirectoryService->suggestions($request->user())
        )->resolve($request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Dashboard carregado com sucesso.',
                'data' => [
                    'current_user' => $currentUser,
                    'suggestions' => $suggestions,
                ],
            ]);
        }

        return Inertia::render('Dashboard/Index', [
            'currentUser' => $currentUser,
            'suggestions' => $suggestions,
        ]);
    }
}
