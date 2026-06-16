<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Campaign;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Données pour les statistiques
        $totalMedia = Media::count();
        $imagesCount = Media::where('type', 'image')->count();
        $videosCount = Media::where('type', 'video')->count();
        $pdfCount = Media::where('type', 'pdf')->count();
        $documentsCount = Media::whereIn('type', ['document', 'doc', 'docx', 'txt'])->count();
        $totalSize = Media::sum('size');
        $totalSizeMB = round($totalSize / 1024 / 1024, 1);
        
        // Derniers médias (pour le widget)
        $recentMedia = Media::orderBy('created_at', 'desc')->limit(10)->get();
        
        // Campagnes actives (pour le widget)
        $activeCampaignsList = Campaign::with('media')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $activeCampaigns = $activeCampaignsList->count();
        $totalCampaigns = Campaign::count();
        
        // Données simulées pour les tendances (à remplacer par de vraies données)
        $newMediaThisMonth = Media::whereMonth('created_at', now()->month)->count();
        $mediaGrowth = 8.4;
        $newCampaignsThisWeek = Campaign::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $iaRequestsToday = rand(5, 25);
        $iaGrowth = rand(-10, 15);
        
        return view('dashboard.index', compact(
            'totalMedia', 'imagesCount', 'videosCount', 'pdfCount', 'documentsCount', 'totalSizeMB',
            'recentMedia', 'activeCampaignsList', 'activeCampaigns', 'totalCampaigns',
            'newMediaThisMonth', 'mediaGrowth', 'newCampaignsThisWeek', 'iaRequestsToday', 'iaGrowth'
        ));
    }
}