<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class GeminiController extends Controller
{
    protected $gemini;
    
    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'type' => 'nullable|string'
        ]);
        
        $response = $this->gemini->generate(
            $request->prompt,
            $request->type ?? 'general'
        );
        
        if ($response) {
            return response()->json([
                'success' => true,
                'response' => $response
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => 'Erreur API Gemini'
        ], 500);
    }
}