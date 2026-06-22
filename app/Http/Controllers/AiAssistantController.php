<?php

namespace App\Http\Controllers;

use App\Models\AiRequest;
use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AiAssistantController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات من قاعدة البيانات (فقط لليوم)
        $stats = [
            'total_requests' => AiRequest::whereDate('created_at', today())->count(),
            'slogans' => AiRequest::where('type', 'slogan')->whereDate('created_at', today())->count(),
            'evaluations' => AiRequest::where('type', 'evaluation')->whereDate('created_at', today())->count(),
            'keywords' => AiRequest::where('type', 'keywords')->whereDate('created_at', today())->count(),
            'avg_score' => $this->calculateAvgScore(),
        ];
        
        // الكلمات المفتاحية الشائعة (كل الوقت)
        $popularKeywords = $this->getPopularKeywords();
        
        // جلب الـ query من الرابط
        $query = $request->query('query', '');
        
        // تمرير الـ query إلى الـ view
        return view('ai.index', compact('stats', 'popularKeywords', 'query'));
    }
    
    private function calculateAvgScore()
    {
        $avg = AiRequest::where('type', 'evaluation')
            ->whereNotNull('response')
            ->whereDate('created_at', today())
            ->get()
            ->map(function($item) {
                preg_match('/(\d+(\.\d+)?)\s*\/\s*10/', $item->response, $matches);
                return isset($matches[1]) ? floatval($matches[1]) : null;
            })
            ->filter()
            ->average();
        
        return $avg ? round($avg, 1) : 7.4;
    }
    
    private function getPopularKeywords()
    {
        $prompts = AiRequest::whereIn('type', ['keywords', 'slogan'])->pluck('prompt')->toArray();
        $allWords = [];
        
        foreach ($prompts as $prompt) {
            $words = preg_split('/[\s,;:.!?()"\'\/\n]+/', strtolower($prompt));
            $allWords = array_merge($allWords, $words);
        }
        
        $stopWords = ['le', 'la', 'les', 'un', 'une', 'des', 'pour', 'avec', 'sans', 'sur', 'sous', 'dans', 'par', 'je', 'tu', 'il', 'elle', 'nous', 'vous', 'ils', 'elles', 'et', 'ou', 'donc', 'mais', 'ni', 'car', 'en', 'de', 'du', 'au', 'aux', 'ce', 'ces', 'cet', 'cette', 'ses', 'mes', 'tes', 'nos', 'vos', 'leurs', 'mon', 'ton', 'son', 'notre', 'votre', 'leur', 'a', 'à', 'est', 'sont', 'était', 'serait', 'être', 'avoir', 'faire', 'dire', 'aller', 'voir', 'savoir', 'pouvoir', 'devoir'];
        $filteredWords = array_diff($allWords, $stopWords);
        $filteredWords = array_filter($filteredWords, function($word) {
            return strlen($word) > 2 && !is_numeric($word) && !empty($word);
        });
        
        $wordCount = array_count_values($filteredWords);
        arsort($wordCount);
        
        return array_slice(array_keys($wordCount), 0, 12);
    }
    
}