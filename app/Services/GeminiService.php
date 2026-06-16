<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    
    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }
    
    public function generate($prompt, $type = 'general')
    {
        try {
            $systemPrompt = $this->getSystemPrompt($type);
            $fullPrompt = $systemPrompt . "\n\n" . $prompt;
            
            $response = Http::timeout(30)->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $this->apiKey,
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1024,
                    ]
                ]
            );
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Désolé, je n'ai pas pu générer une réponse.";
            }
            
            Log::error('Gemini API Error: ' . $response->body());
            return null;
            
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return null;
        }
    }
    
    private function getSystemPrompt($type)
    {
        $prompts = [
            'slogan' => "Tu es un expert en marketing digital. Génère des slogans créatifs en français. Sois professionnel et utilise des émojis.",
            'keywords' => "Tu es un spécialiste SEO. Propose des hashtags et mots-clés pertinents en français.",
            'evaluate' => "Tu es consultant marketing. Évalue ce message en français (score/10, points forts, points faibles, conseils).",
            'visual' => "Tu es directeur artistique. Propose une idée de visuel détaillée en français.",
            'description' => "Tu es rédacteur publicitaire. Rédige une description impactante en français.",
            'general' => "Tu es un assistant marketing IA qui parle français. Réponds de façon professionnelle et amicale."
        ];
        
        return $prompts[$type] ?? $prompts['general'];
    }
}