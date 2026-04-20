<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserDirectoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserDirectoryController extends Controller
{
    public function __construct(
        protected UserDirectoryService $userDirectoryService,
    ) {
    }

    public function index(Request $request): Response|\Illuminate\Http\JsonResponse
    {
        $search = trim((string) $request->string('search'));
        $users = $this->userDirectoryService->search($request->user(), $search ?: null);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Usuarios carregados com sucesso.',
                'data' => UserResource::collection($users->getCollection()),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ]);
        }

        return Inertia::render('Users/Index', [
            'filters' => ['search' => $search],
            'users' => [
                'data' => UserResource::collection($users->getCollection())->resolve($request),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ],
        ]);
    }
}
