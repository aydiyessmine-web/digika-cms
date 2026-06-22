<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_' . $key;
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $value = Setting::where('key', $key)->value('value');
        
        if ($value !== null) {
            Cache::put($cacheKey, $value, 3600);
            return $value;
        }
        
        return $default;
    }

    public static function getGroup($group)
    {
        $cacheKey = 'settings_group_' . $group;
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $settings = Setting::where('group', $group)->pluck('value', 'key')->toArray();
        Cache::put($cacheKey, $settings, 3600);
        
        return $settings;
    }

    public static function clearCache()
    {
        Cache::forget('settings_group_display');
        Cache::forget('settings_group_security');
        Cache::forget('settings_group_ai');
        Cache::forget('settings_group_notifications');
        
        $keys = ['default_theme', 'media_per_page', 'campaigns_per_page', 
                 'session_expiry', 'two_factor_auth', 'password_policy', 'login_attempts',
                 'ai_provider', 'ai_api_key', 'ai_model', 'ai_daily_limit', 'ai_temperature',
                 'ai_logs_enabled', 'ai_status'];
        foreach ($keys as $key) {
            Cache::forget('setting_' . $key);
        }
    }

    public static function getTheme()
    {
        return self::get('default_theme', 'light');
    }

    public static function getMediaPerPage()
    {
        return (int) self::get('media_per_page', 10);
    }

    public static function getCampaignsPerPage()
    {
        return (int) self::get('campaigns_per_page', 6);
    }

    public static function isDarkMode()
    {
        return self::getTheme() === 'dark';
    }

    public static function getThemeClass()
    {
        return self::isDarkMode() ? 'theme-dark' : 'theme-light';
    }

    public static function getViewSettings()
    {
        return [
            'theme' => self::getTheme(),
            'media_per_page' => self::getMediaPerPage(),
            'campaigns_per_page' => self::getCampaignsPerPage(),
            'is_dark' => self::isDarkMode(),
            'theme_class' => self::getThemeClass(),
        ];
    }
    /**
 * Vérifier si la politique de mot de passe est stricte
 */
public static function isPasswordPolicyStrict()
{
    $policy = self::get('password_policy', 'basic');
    return $policy !== 'basic';
}

/**
 * Obtenir les règles de mot de passe
 */
public static function getPasswordRules()
{
    $policy = self::get('password_policy', 'basic');
    $rules = [];
    
    if ($policy === 'basic') {
        $rules[] = 'min:8';
    } elseif ($policy === 'advanced') {
        $rules[] = 'min:12';
        $rules[] = 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/';
    } elseif ($policy === 'strict') {
        $rules[] = 'min:16';
        $rules[] = 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{16,}$/';
    }
    
    return $rules;
}

/**
 * Obtenir le message d'erreur pour le mot de passe
 */
public static function getPasswordErrorMessage()
{
    $policy = self::get('password_policy', 'basic');
    
    if ($policy === 'basic') {
        return 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($policy === 'advanced') {
        return 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
    } elseif ($policy === 'strict') {
        return 'Le mot de passe doit contenir au moins 16 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
    }
    
    return 'Le mot de passe doit respecter les règles de sécurité.';
}
}