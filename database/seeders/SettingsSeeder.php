<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // AI Settings
            ['group' => 'ai', 'key' => 'ai_provider', 'value' => 'openai', 'description' => 'Fournisseur API IA'],
            ['group' => 'ai', 'key' => 'ai_api_key', 'value' => 'sk-demo123456789abcdef', 'description' => 'Clé API'],
            ['group' => 'ai', 'key' => 'ai_model', 'value' => 'gpt-4-turbo-preview', 'description' => 'Modèle par défaut'],
            ['group' => 'ai', 'key' => 'ai_daily_limit', 'value' => '500', 'description' => 'Limite quotidienne'],
            ['group' => 'ai', 'key' => 'ai_temperature', 'value' => '0.7', 'description' => 'Température créative'],
            ['group' => 'ai', 'key' => 'ai_logs_enabled', 'value' => 'true', 'description' => 'Activer les logs IA'],
            ['group' => 'ai', 'key' => 'ai_status', 'value' => 'connected', 'description' => 'Statut de connexion'],
            
            // Security Settings
            ['group' => 'security', 'key' => 'session_expiry', 'value' => '60', 'description' => 'Expiration de session (minutes)'],
            ['group' => 'security', 'key' => 'two_factor_auth', 'value' => 'false', 'description' => 'Authentification à deux facteurs'],
            ['group' => 'security', 'key' => 'password_policy', 'value' => 'advanced', 'description' => 'Politique mot de passe'],
            ['group' => 'security', 'key' => 'login_attempts', 'value' => '5', 'description' => 'Tentatives de connexion'],
            ['group' => 'security', 'key' => 'log_retention_days', 'value' => '90', 'description' => 'Conservation logs connexion'],
            ['group' => 'security', 'key' => 'auto_block_enabled', 'value' => 'true', 'description' => 'Blocage automatique'],
            ['group' => 'security', 'key' => 'log_all_attempts', 'value' => 'true', 'description' => 'Enregistrer toutes les tentatives'],
            ['group' => 'security', 'key' => 'alert_unknown_device', 'value' => 'true', 'description' => 'Alerte email pour appareil inconnu'],
            ['group' => 'security', 'key' => 'unique_session', 'value' => 'true', 'description' => 'Session unique'],
            ['group' => 'security', 'key' => 'auto_logout_inactive', 'value' => 'true', 'description' => 'Déconnexion après inactivité'],
            ['group' => 'security', 'key' => 'inactivity_timeout', 'value' => '15', 'description' => 'Délai d\'inactivité (minutes)'],
            
            // Display Settings - Media per page (10, 15, 20)
            ['group' => 'display', 'key' => 'default_theme', 'value' => 'light', 'description' => 'Thème par défaut'],
            ['group' => 'display', 'key' => 'media_per_page', 'value' => '10', 'description' => 'Médias par page'],
            ['group' => 'display', 'key' => 'campaigns_per_page', 'value' => '6', 'description' => 'Campagnes par page'],
            
            // Notification Settings
            ['group' => 'notifications', 'key' => 'email_notifications', 'value' => 'true', 'description' => 'Notifications par email'],
            ['group' => 'notifications', 'key' => 'push_notifications', 'value' => 'false', 'description' => 'Notifications push'],
            ['group' => 'notifications', 'key' => 'campaign_alerts', 'value' => 'true', 'description' => 'Alertes campagnes'],
            ['group' => 'notifications', 'key' => 'security_alerts', 'value' => 'true', 'description' => 'Alertes sécurité'],
            // Ajouter dans le tableau $settings
            ['group' => 'security', 'key' => 'block_duration', 'value' => '15', 'description' => 'Durée de blocage (minutes)'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
    }
}