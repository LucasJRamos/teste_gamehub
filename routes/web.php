<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
});

Route::fallback(fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'));

