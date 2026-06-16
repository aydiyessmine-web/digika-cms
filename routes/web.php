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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// Route للذكاء الاصطناعي - يدعم الفرنسية والعربية والتونسية
// ============================================
Route::post('/ai/chat', function (Request $request) {
    $prompt = $request->input('prompt');
    
    // نظام prompt متعدد اللغات
    $systemPrompt = "أنت مساعد ذكاء اصطناعي ذكي ومحترف. اسمك DigiCMS IA. أنت تتحدث ثلاث لغات: الفرنسية والعربية الفصحى والدارجة التونسية. 
    
    قواعد الرد:
    1. إذا كتب المستخدم بالفرنسية → رد بالفرنسية
    2. إذا كتب المستخدم بالعربية الفصحى → رد بالعربية الفصحى
    3. إذا كتب المستخدم بالدارجة التونسية → رد بالدارجة التونسية
    
    أنت خبير في التسويق الرقمي والإعلانات الديناميكية. تستطيع:
    - توليد شعارات إعلانية (slogans) 
    - اقتراح كلمات مفتاحية
    - تقييم الرسائل التسويقية
    - اقتراح أفكار بصرية
    - كتابة وصف للإعلانات
    
    كن ودوداً ومحترفاً ومبدعاً.";
    
    // 🔑 مفتاح Groq تاعك
    $apiKey = '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';
    
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false,
            'timeout' => 60,
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            // ✅ استعمل الموديل الجديد
            'model' => 'llama-3.1-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 800
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'success' => true,
                'response' => $data['choices'][0]['message']['content'] ?? 'Pas de réponse'
            ]);
        }
        
        // إذا فشل، جرب موديل آخر
        $response2 = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false,
            'timeout' => 60,
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'mixtral-8x7b-32768',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 800
        ]);
        
        if ($response2->successful()) {
            $data = $response2->json();
            return response()->json([
                'success' => true,
                'response' => $data['choices'][0]['message']['content'] ?? 'Pas de réponse'
            ]);
        }
        
        // إذا فشل كل شيء، إستعمل Ollama
        try {
            $ollamaResponse = Http::withOptions([
                'timeout' => 60,
            ])->post('http://localhost:11434/api/chat', [
                'model' => 'llama3:8b',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'stream' => false,
                'options' => [
                    'temperature' => 0.7,
                    'num_predict' => 800
                ]
            ]);
            
            if ($ollamaResponse->successful()) {
                $data = $ollamaResponse->json();
                return response()->json([
                    'success' => true,
                    'response' => $data['message']['content'] ?? 'Je suis DigiCMS IA.'
                ]);
            }
        } catch (\Exception $e) {}
        
        return response()->json([
            'success' => false,
            'error' => 'Erreur API: ' . $response->body()
        ], 500);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
})->name('ai.chat');

// Routes d'authentification
Auth::routes();
Route::get('/media/{id}/content', [App\Http\Controllers\MediaController::class, 'getContent'])->name('media.content');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/ai-assistant', [AiAssistantController::class, 'index'])->name('ai.assistant');
    Route::post('/ai/generate', [AiAssistantController::class, 'generate'])->name('ai.generate');
    Route::resource('categories', CategoryController::class)->middleware('role:admin,marketing');
    Route::resource('campaigns', CampaignController::class)->middleware('role:admin,marketing');
    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update')->middleware('role:admin,marketing');
    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy')->middleware('role:admin,marketing');
    Route::resource('media', MediaController::class)->middleware('role:admin,marketing');
    Route::resource('screens', ScreenController::class)->middleware('role:admin,marketing');
    Route::resource('settings', SettingController::class)->middleware('role:admin');
    Route::resource('profiles', ProfileController::class)->middleware('role:admin');
    Route::get('/campaigns/{id}/media', [CampaignController::class, 'getCampaignMedia']);
    Route::delete('/campaigns/{campaignId}/media/{mediaId}', [CampaignController::class, 'removeMediaFromCampaign']);
    Route::get('/media/{id}/content', [MediaController::class, 'getFileContent']);
    Route::get('/pdf-viewer/{filename}', [PdfController::class, 'show'])->name('pdf.viewer');
    Route::get('/pdf-file/{filename}', [PdfController::class, 'getPdf'])->name('pdf.file');
    Route::get('/ai-assistant', function () {
        return view('ai.index');
    })->name('ai.assistant');
});