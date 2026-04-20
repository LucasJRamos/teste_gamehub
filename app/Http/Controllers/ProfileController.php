<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\StorePortfolioItemRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\PortfolioItemResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\PortfolioItem;
use App\Models\User;
use App\Services\ProfileService;
use App\Services\SocialGraphService;
use App\Services\UserDirectoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService,
        protected SocialGraphService $socialGraphService,
        protected UserDirectoryService $userDirectoryService,
    ) {
    }

    public function show(Request $request): Response
    {
        $user = $this->profileService->loadProfile($request->user());

        return Inertia::render('Profiles/Show', [
            'profile' => ProfileResource::make($user)->resolve($request),
            'suggestions' => UserResource::collection(
                $this->userDirectoryService->suggestions($request->user(), 3)
            )->resolve($request),
        ]);
    }

    public function showUser(Request $request, User $user): Response
    {
        $this->socialGraphService->ensureVisible($request->user(), $user);
        $profile = $this->profileService->loadProfile($user);

        return Inertia::render('Profiles/Show', [
            'profile' => ProfileResource::make($profile)->resolve($request),
            'suggestions' => UserResource::collection(
                $this->userDirectoryService->suggestions($request->user(), 3)
            )->resolve($request),
        ]);
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('Profiles/Edit', [
            'profile' => UserResource::make(
                $request->user()->loadCount(['followers', 'following', 'portfolioItems'])
            )->resolve($request),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $this->profileService->update($request->user(), $request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Perfil atualizado com sucesso!',
                'data' => UserResource::make($user),
            ]);
        }

        return to_route('profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function uploadPortfolio(StorePortfolioItemRequest $request)
    {
        $item = $this->profileService->addPortfolioItem($request->user(), $request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item adicionado ao portfolio!',
                'data' => PortfolioItemResource::make($item),
            ], 201);
        }

        return to_route('profile')->with('success', 'Item adicionado ao portfolio!');
    }

    public function deletePortfolioItem(Request $request, PortfolioItem $portfolioItem)
    {
        $this->profileService->deletePortfolioItem($request->user(), $portfolioItem);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item removido do portfolio!',
            ]);
        }

        return to_route('profile')->with('success', 'Item removido do portfolio!');
    }
}
