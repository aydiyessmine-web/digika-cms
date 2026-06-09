<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Campaign;
use App\Models\AiRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $mediaCount    = Media::count();
        $campaignCount = Campaign::count();
        $aiCount       = AiRequest::count();

        // Screen count — utilisera le model Screen quand on le crée
        $screenCount = 0;
        if (class_exists(\App\Models\Screen::class)) {
            $screenCount = \App\Models\Screen::count();
        }

        $recentMedia = Media::latest()->take(6)->get();

        return view('dashboard.index', compact(
            'mediaCount',
            'campaignCount',
            'aiCount',
            'screenCount',
            'recentMedia'
        ));
    }
}
