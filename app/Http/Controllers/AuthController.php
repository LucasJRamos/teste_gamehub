<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'username'        => 'required|string|max:100|unique:users,username',
            'email'           => 'required|email|unique:users,email',
            'data_nascimento' => 'required|date',
            'password'        => 'required|confirmed|min:6',
        ]);

        User::create([
            'username'        => $request->username,
            'email'           => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'password'        => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso!');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

    public function dashboard() {
        return view('dashboard');
    }
}

?>