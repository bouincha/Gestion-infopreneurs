<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\InfoproneurController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil publique
Route::get('/', LandingController::class)->name('home');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Tableau de bord
    Route::get('/index', DashboardController::class)->name('dashboard');

    // Gestion des infoproneurs, projets et domaines
    Route::resource('infoproneurs', InfoproneurController::class);
    Route::resource('projets', ProjetController::class);
    Route::resource('domaines', DomaineController::class)->except(['create', 'show']);

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Photos uploadées (storage/app/public)
    Route::get('/media/{path}', MediaController::class)->where('path', '.*')->name('media');
});
