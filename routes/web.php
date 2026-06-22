<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PdfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AiRequest;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// Route pour l'intelligence artificielle - avec Groq API et sauvegarde en base de données
// ============================================
Route::post('/ai/chat', function (Request $request) {
    $prompt = $request->input('prompt');
    $type = $request->input('type', 'general');
    
    $sessionId = $request->input('session_id');
    if (!$sessionId) {
        $sessionId = 'conv_' . Auth::id() . '_' . time() . '_' . uniqid();
    }
    
    $typeMap = [
        'slogan' => 'slogan',
        'keywords' => 'keywords',
        'evaluate' => 'evaluation',
        'visual' => 'visual_idea',
        'description' => 'description',
    ];
    $requestType = $typeMap[$type] ?? 'general';
    
    $systemPrompt = "Vous êtes un assistant IA intelligent et professionnel. Votre nom est DigiCMS IA. Vous parlez trois langues : le français, l'arabe littéraire et le tunisien (arabe dialectal tunisien).
    
    Règles de réponse :
    1. Si l'utilisateur écrit en français → répondre en français
    2. Si l'utilisateur écrit en arabe littéraire → répondre en arabe littéraire
    3. Si l'utilisateur écrit en tunisien (dialecte tunisien) → répondre en tunisien
    
    Vous êtes un expert en marketing digital et en publicité dynamique.
    Vous pouvez générer des slogans publicitaires, proposer des mots-clés, évaluer des messages marketing, suggérer des idées visuelles et rédiger des descriptions pour les publicités.
    
    Soyez amical, professionnel et créatif.";
    
    $apiKey = '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';
    
    $models = [
        'llama-3.3-70b-versatile',
        'llama-3.1-8b-instant',
    ];
    
    $lastError = null;
    $responseText = null;
    
    foreach ($models as $model) {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'timeout' => 60,
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 800
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $responseText = $data['choices'][0]['message']['content'] ?? 'Pas de réponse';
                
                if (Auth::check()) {
                    AiRequest::create([
                        'user_id' => Auth::id(),
                        'type' => $requestType,
                        'prompt' => $prompt,
                        'response' => $responseText,
                    ]);
                    
                    ChatHistory::create([
                        'user_id' => Auth::id(),
                        'session_id' => $sessionId,
                        'role' => 'user',
                        'content' => $prompt,
                    ]);
                    
                    ChatHistory::create([
                        'user_id' => Auth::id(),
                        'session_id' => $sessionId,
                        'role' => 'assistant',
                        'content' => $responseText,
                    ]);
                }
                
                $stats = [
                    'total_requests' => AiRequest::whereDate('created_at', today())->count(),
                    'slogans' => AiRequest::where('type', 'slogan')->whereDate('created_at', today())->count(),
                    'evaluations' => AiRequest::where('type', 'evaluation')->whereDate('created_at', today())->count(),
                    'keywords' => AiRequest::where('type', 'keywords')->whereDate('created_at', today())->count(),
                ];
                
                return response()->json([
                    'success' => true,
                    'response' => $responseText,
                    'stats' => $stats,
                    'model_used' => $model,
                    'session_id' => $sessionId
                ]);
            }
            
            $lastError = $response->body();
            
        } catch (\Exception $e) {
            $lastError = $e->getMessage();
        }
    }
    
    return response()->json([
        'success' => false,
        'error' => 'Erreur API: ' . $lastError
    ], 500);
    
})->name('ai.chat');

// ============================================
// Routes pour l'historique des conversations
// ============================================
Route::get('/ai/history', function () {
    try {
        $sessions = ChatHistory::where('user_id', Auth::id())
            ->whereNotNull('session_id')
            ->select('session_id', DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('session_id')
            ->orderBy('last_activity', 'desc')
            ->limit(10)
            ->get();
        
        $history = [];
        foreach ($sessions as $session) {
            $messages = ChatHistory::where('user_id', Auth::id())
                ->where('session_id', $session->session_id)
                ->orderBy('created_at', 'asc')
                ->get();
            
            if ($messages->count() > 0) {
                $firstUserMsg = $messages->where('role', 'user')->first();
                
                $title = 'Conversation';
                if ($firstUserMsg) {
                    $content = $firstUserMsg->content;
                    $lowerContent = strtolower($content);
                    
                    if (strpos($lowerContent, 'slogan') !== false) {
                        $title = '🎯 Slogans – ' . substr($content, 0, 30) . '...';
                    } elseif (strpos($lowerContent, 'mot-clé') !== false || strpos($lowerContent, 'hashtag') !== false) {
                        $title = '🔑 Mots-clés – ' . substr($content, 0, 30) . '...';
                    } elseif (strpos($lowerContent, 'évalue') !== false) {
                        $title = '📊 Évaluation – ' . substr($content, 0, 30) . '...';
                    } elseif (strpos($lowerContent, 'visuel') !== false) {
                        $title = '🎨 Idée visuel – ' . substr($content, 0, 30) . '...';
                    } elseif (strpos($lowerContent, 'description') !== false) {
                        $title = '📝 Description – ' . substr($content, 0, 30) . '...';
                    } elseif (strpos($lowerContent, 'bonjour') !== false || strpos($lowerContent, 'salut') !== false || strpos($lowerContent, 'hi') !== false) {
                        $title = '💬 Salutation';
                    } else {
                        $title = '💬 ' . substr($content, 0, 40) . (strlen($content) > 40 ? '...' : '');
                    }
                }
                
                $history[] = [
                    'session_id' => $session->session_id,
                    'title' => $title,
                    'time' => $session->last_activity ? \Carbon\Carbon::parse($session->last_activity)->diffForHumans() : 'Récemment',
                    'messages' => $messages->map(function($msg) {
                        return [
                            'role' => $msg->role,
                            'content' => $msg->content,
                        ];
                    })
                ];
            }
        }
        
        return response()->json($history);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
})->middleware('auth')->name('ai.history');

Route::delete('/ai/history', function () {
    try {
        ChatHistory::where('user_id', Auth::id())->delete();
        AiRequest::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->middleware('auth')->name('ai.history.clear');

// ============================================
// Routes d'authentification
// ============================================
Auth::routes();
Route::get('/media/{id}/content', [App\Http\Controllers\MediaController::class, 'getContent'])->name('media.content');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ============================================
// Routes protégées - Accessibles à tous les utilisateurs connectés
// ============================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & AI
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/ai-assistant', [AiAssistantController::class, 'index'])->name('ai.assistant');
    Route::post('/ai/generate', [AiAssistantController::class, 'generate'])->name('ai.generate');
    
    // ============================================
    // Médiathèque - Accessible à admin & marketing
    // ============================================
    Route::resource('media', MediaController::class);
    Route::get('/media/{id}/content', [MediaController::class, 'getFileContent']);
    
    // ============================================
    // Campagnes - Accessible à admin & marketing
    // ============================================
    Route::resource('campaigns', CampaignController::class);
    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::get('/campaigns/{id}/media', [CampaignController::class, 'getCampaignMedia']);
    Route::delete('/campaigns/{campaignId}/media/{mediaId}', [CampaignController::class, 'removeMediaFromCampaign']);
    
    
    // ============================================
    // PDF Viewer - Accessible à admin & marketing
    // ============================================
    Route::get('/pdf-viewer/{filename}', [PdfController::class, 'show'])->name('pdf.viewer');
    Route::get('/pdf-file/{filename}', [PdfController::class, 'getPdf'])->name('pdf.file');
});

// ============================================
// Routes accessibles seulement à admin
// ============================================

// Routes pour les paramètres (admin seulement)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/group/{group}', [SettingController::class, 'updateGroup'])->name('settings.update.group');
    Route::post('/settings/ai/test', [SettingController::class, 'testAiConnection'])->name('settings.ai.test');
    Route::post('/settings/history/clear', [SettingController::class, 'clearHistory'])->name('settings.history.clear');
    Route::get('/settings/logs/export', [SettingController::class, 'exportLogs'])->name('settings.logs.export');
    Route::post('/settings/session/{id}/revoke', [SettingController::class, 'revokeSession'])->name('settings.session.revoke');
});

// Route pour récupérer les paramètres (AJAX)
Route::get('/settings/get', function() {
    return response()->json([
        'theme' => \App\Helpers\SettingsHelper::getTheme(),
        'media_per_page' => \App\Helpers\SettingsHelper::getMediaPerPage(),
        'campaigns_per_page' => \App\Helpers\SettingsHelper::getCampaignsPerPage(),
        'is_dark' => \App\Helpers\SettingsHelper::isDarkMode(),
    ]);
})->middleware('auth')->name('settings.get');


// Routes pour les profils (admin seulement)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
    Route::put('/profiles/{id}', [ProfileController::class, 'update'])->name('profiles.update');
    Route::post('/profiles/{id}/toggle-active', [ProfileController::class, 'toggleActive'])->name('profiles.toggle-active');
    Route::delete('/profiles/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
    Route::get('/profiles/export', [ProfileController::class, 'export'])->name('profiles.export');
});