<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas (requer autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Rotas de Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}', [ProfileController::class, 'showUser'])->name('profile.show');
    
    // Rotas de Portfólio
    Route::post('/portfolio/upload', [ProfileController::class, 'uploadPortfolio'])->name('portfolio.upload');
    Route::delete('/portfolio/{id}', [ProfileController::class, 'deletePortfolioItem'])->name('portfolio.delete');
    
    // Rota de Explorar
    Route::get('/explore', [ProfileController::class, 'explore'])->name('explore');
});