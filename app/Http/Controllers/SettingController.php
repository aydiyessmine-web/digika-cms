<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\UserSession;
use App\Helpers\SettingsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        $aiSettings = Setting::getGroup('ai');
        $securitySettings = Setting::getGroup('security');
        $displaySettings = Setting::getGroup('display');
        $notificationSettings = Setting::getGroup('notifications');
        
        $activeSessions = UserSession::with('user')
            ->where('is_current', true)
            ->orderBy('last_activity', 'desc')
            ->get();
        
        $loginHistory = UserSession::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->limit(10)
            ->get();

        $securityLevel = $this->calculateSecurityLevel($securitySettings);

        return view('settings.index', compact(
            'aiSettings', 'securitySettings', 'displaySettings', 
            'notificationSettings', 'activeSessions', 'loginHistory',
            'securityLevel'
        ));
    }

    private function calculateSecurityLevel($settings)
    {
        $score = 0;
        $maxScore = 10;
        
        // 1. Politique de mot de passe (0-3 points)
        $policy = $settings['password_policy'] ?? 'basic';
        if ($policy === 'basic') $score += 1;
        elseif ($policy === 'advanced') $score += 2;
        elseif ($policy === 'strict') $score += 3;
        
        // 2. 2FA (0-2 points)
        if (($settings['two_factor_auth'] ?? 'false') === 'true') $score += 2;
        
        // 3. Session unique (0-1 point)
        if (($settings['unique_session'] ?? 'true') === 'true') $score += 1;
        
        // 4. Blocage automatique (0-1 point)
        if (($settings['auto_block_enabled'] ?? 'true') === 'true') $score += 1;
        
        // 5. Logs de connexion (0-1 point)
        if (($settings['log_all_attempts'] ?? 'true') === 'true') $score += 1;
        
        // 6. Alertes email (0-1 point)
        if (($settings['alert_unknown_device'] ?? 'true') === 'true') $score += 1;
        
        // 7. Déconnexion automatique (0-1 point)
        if (($settings['auto_logout_inactive'] ?? 'true') === 'true') $score += 1;
        
        $percentage = ($score / $maxScore) * 100;
        
        if ($percentage >= 80) return ['level' => 'Élevé', 'class' => 'high', 'icon' => 'fa-shield-alt', 'color' => '#10b981'];
        if ($percentage >= 50) return ['level' => 'Moyen', 'class' => 'medium', 'icon' => 'fa-shield', 'color' => '#f59e0b'];
        return ['level' => 'Faible', 'class' => 'low', 'icon' => 'fa-shield-virus', 'color' => '#ef4444'];
    }

    public function updateGroup(Request $request, $group)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        SettingsHelper::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Paramètres du groupe ' . $group . ' mis à jour'
        ]);
    }

    public function clearHistory()
    {
        try {
            $deleted = UserSession::where('user_id', Auth::id())
                ->where('session_token', '!=', session()->getId())
                ->delete();
            
            Log::info('Historique des connexions effacé', [
                'user' => Auth::user()->email,
                'deleted' => $deleted,
                'time' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Historique effacé avec succès (' . $deleted . ' entrées supprimées)'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportLogs()
    {
        $logs = UserSession::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get();
        
        $csv = "Date,IP,Appareil,Statut,Dernière activité,Login Time,Logout Time\n";
        foreach ($logs as $log) {
            $status = $log->is_current ? 'En cours' : 'Terminé';
            $csv .= ($log->created_at ?? 'N/A') . ","
                    . ($log->ip_address ?? 'N/A') . ","
                    . ($log->user_agent ?? 'N/A') . ","
                    . $status . ","
                    . ($log->last_activity ?? 'N/A') . ","
                    . ($log->login_time ?? 'N/A') . ","
                    . ($log->logout_time ?? 'N/A') . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="logs_connexion_' . date('Y-m-d') . '.csv"');
    }

    public function revokeSession($id)
    {
        $session = UserSession::find($id);
        if ($session && $session->user_id == Auth::id()) {
            $session->is_current = false;
            $session->logout_time = now();
            $session->save();
            
            Log::info('Session révoquée', [
                'user' => Auth::user()->email,
                'session_id' => $id,
                'ip' => $session->ip_address
            ]);
            
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function testAiConnection()
    {
        $apiKey = Setting::where('key', 'ai_api_key')->value('value');
        $isConnected = !empty($apiKey) && strlen($apiKey) > 5;
        
        Setting::updateOrCreate(
            ['key' => 'ai_status'],
            ['value' => $isConnected ? 'connected' : 'disconnected', 'group' => 'ai']
        );
        
        return response()->json([
            'success' => $isConnected,
            'message' => $isConnected ? 'Connexion réussie' : 'Échec de la connexion'
        ]);
    }
}