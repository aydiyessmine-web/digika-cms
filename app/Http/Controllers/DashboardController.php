<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Media;
use App\Models\AiRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques pour les cartes
        $totalMedia = Media::count();
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $iaRequestsToday = AiRequest::whereDate('created_at', today())->count();
        
        // Calculer le nombre de nouvelles campagnes cette semaine
        $newCampaignsThisWeek = Campaign::where('created_at', '>=', now()->startOfWeek())->count();
        
        // Médias récents - 5 médias
        $recentMedia = Media::latest()->take(5)->get();
        
        // Campagnes actives - 5 campagnes (au lieu de 3)
        $activeCampaignsList = Campaign::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Données par défaut pour screens (pour éviter les erreurs)
        $totalScreens = 0;
        $connectedScreens = 0;
        $connectedScreensList = [];
        
        return view('dashboard.index', compact(
            'totalMedia',
            'totalCampaigns', 
            'activeCampaigns',
            'totalScreens',
            'connectedScreens',
            'iaRequestsToday',
            'newCampaignsThisWeek',
            'recentMedia',
            'activeCampaignsList',
            'connectedScreensList'
        ));
    }
}