<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'data_nascimento' => $data['data_nascimento'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function attempt(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }
}
