<?php

use App\Http\Controllers\Api\GeminiController;

Route::post('/ai/generate', [GeminiController::class, 'generate']);