<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class ApplySettings
{
    public function handle($request, Closure $next)
    {
        // Charger les paramètres
        $settings = SettingsHelper::getViewSettings();
        
        // Partager avec toutes les vues
        View::share('appSettings', $settings);
        
        // Stocker dans la session pour un accès facile
        Session::put('app_theme', $settings['theme']);
        Session::put('app_is_dark', $settings['is_dark']);
        Session::put('app_theme_class', $settings['theme_class']);
        
        return $next($request);
    }
}