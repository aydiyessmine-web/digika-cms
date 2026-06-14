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
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification (gérées par Laravel UI)
Auth::routes();
Route::get('/media/{id}/content', [App\Http\Controllers\MediaController::class, 'getContent'])->name('media.content');

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
    Route::get('/ai-assistant', [AiAssistantController::class, 'index'])->name('ai.assistant');
    Route::post('/ai/generate', [AiAssistantController::class, 'generate'])->name('ai.generate');
    
    // Catégories (admin + marketing)
    Route::resource('categories', CategoryController::class)->middleware('role:admin,marketing');
    
    // Campagnes (admin + marketing)
    Route::resource('campaigns', CampaignController::class)->middleware('role:admin,marketing');
    // Routes supplémentaires pour les campagnes (mises à jour et suppression)
    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update')->middleware('role:admin,marketing');
    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy')->middleware('role:admin,marketing');
    
    // Médias (admin + marketing)
    Route::resource('media', MediaController::class)->middleware('role:admin,marketing');
    
    // Écrans (admin + marketing)
    Route::resource('screens', ScreenController::class)->middleware('role:admin,marketing');
    
    // Paramètres (admin uniquement)
    Route::resource('settings', SettingController::class)->middleware('role:admin');
    
    // Profils / Gestion utilisateurs (admin uniquement)
    Route::resource('profiles', ProfileController::class)->middleware('role:admin');

    Route::get('/campaigns/{id}/media', [CampaignController::class, 'getCampaignMedia']);
    Route::delete('/campaigns/{campaignId}/media/{mediaId}', [CampaignController::class, 'removeMediaFromCampaign']);

    Route::get('/media/{id}/content', [MediaController::class, 'getFileContent']);
    Route::get('/pdf-viewer/{filename}', [PdfController::class, 'show'])->name('pdf.viewer');
    Route::get('/pdf-file/{filename}', [PdfController::class, 'getPdf'])->name('pdf.file');
});