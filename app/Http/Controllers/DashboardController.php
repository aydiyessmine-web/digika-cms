<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Campaign;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard fixe - toujours 10 médias récents
        $totalMedia = Media::count();
        $imagesCount = Media::where('type', 'image')->count();
        $videosCount = Media::where('type', 'video')->count();
        $pdfCount = Media::where('type', 'pdf')->count();
        $documentsCount = Media::where('type', 'document')->count();
        $webpagesCount = Media::where('type', 'webpage')->count();
        $totalSize = Media::sum('size');
        $totalSizeMB = round($totalSize / 1024 / 1024, 1);
        
        // Dashboard: toujours 10 médias récents
        $recentMedia = Media::orderBy('created_at', 'desc')->limit(10)->get();
        
        $activeCampaignsList = Campaign::with('media')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $activeCampaigns = $activeCampaignsList->count();
        $totalCampaigns = Campaign::count();
        
        $newMediaThisMonth = Media::whereMonth('created_at', now()->month)->count();
        $mediaGrowth = 8.4;
        $newCampaignsThisWeek = Campaign::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $iaRequestsToday = rand(5, 25);
        $iaGrowth = rand(-10, 15);
        
        return view('dashboard.index', compact(
            'totalMedia', 'imagesCount', 'videosCount', 'pdfCount', 'documentsCount', 
            'webpagesCount', 'totalSizeMB', 'recentMedia', 'activeCampaignsList', 
            'activeCampaigns', 'totalCampaigns', 'newMediaThisMonth', 'mediaGrowth', 
            'newCampaignsThisWeek', 'iaRequestsToday', 'iaGrowth'
        ));
    }
}