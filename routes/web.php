<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification (gérées par Laravel UI)
Auth::routes();

// Routes publiques (pas besoin d'auth)
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Routes protégées (nécessitent authentification)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard (accessible par tous les rôles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);
    
    // Assistant IA (accessible par tous les rôles)
    Route::get('/ai-assistant', [AiAssistantController::class, 'index'])->name('ai.index');
    Route::post('/ai/generate', [AiAssistantController::class, 'generate'])->name('ai.generate');
    
    // Catégories (admin + marketing)
    Route::resource('categories', CategoryController::class)->middleware('role:admin,marketing');
    
    // Campagnes (admin + marketing)
    Route::resource('campaigns', CampaignController::class)->middleware('role:admin,marketing');
    
    // Médias (admin + marketing)
    Route::resource('media', MediaController::class)->middleware('role:admin,marketing');
    
    // Écrans (admin + marketing)
    Route::resource('screens', ScreenController::class)->middleware('role:admin,marketing');
    
    // Paramètres (admin uniquement)
    Route::resource('settings', SettingController::class)->middleware('role:admin');
    
    // Profils / Gestion utilisateurs (admin uniquement)
    Route::resource('profiles', ProfileController::class)->middleware('role:admin');
});