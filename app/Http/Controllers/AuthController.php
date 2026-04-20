<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {
    }

    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function showRegisterForm(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cadastro realizado com sucesso!',
                'data' => UserResource::make($user->loadCount(['followers', 'following', 'portfolioItems'])),
            ], 201);
        }

        return to_route('dashboard')->with('success', 'Cadastro realizado com sucesso!');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->safe()->only(['email', 'password']);

        if (! $this->authService->attempt($credentials)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Credenciais invalidas.',
                    'errors' => ['email' => ['Credenciais invalidas.']],
                ], 401);
            }

            throw ValidationException::withMessages([
                'email' => 'Credenciais invalidas.',
            ]);
        }

        $request->session()->regenerate();
        $user = $request->user()->loadCount(['followers', 'following', 'portfolioItems']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'data' => UserResource::make($user),
            ]);
        }

        return to_route('dashboard')->with('success', 'Login realizado com sucesso!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Logout realizado com sucesso!',
            ]);
        }

        return to_route('login')->with('success', 'Sessao encerrada com sucesso.');
    }
}
