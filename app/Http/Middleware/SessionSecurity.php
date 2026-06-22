<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserSession;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SessionSecurity
{
    public function handle($request, Closure $next)
    {
        // ============================================
        // VÉRIFICATION DES TENTATIVES DE CONNEXION
        // ============================================
        if ($request->is('login') && $request->isMethod('post')) {
            $maxAttempts = (int) SettingsHelper::get('login_attempts', 5);
            $failedAttempts = session('failed_login_attempts', 0);
            $blockDuration = (int) SettingsHelper::get('block_duration', 15);
            
            $user = \App\Models\User::where('email', $request->email)->first();
            
            if ($user) {
                // Vérifier si le compte est bloqué temporairement
                if ($user->blocked_until && $user->blocked_until->isFuture()) {
                    $remaining = $user->getBlockedTimeRemaining();
                    return redirect()->back()
                        ->withInput($request->only('email', 'role'))
                        ->withErrors([
                            'email' => 'Compte bloqué. Réessayez dans ' . $remaining . '.',
                        ]);
                }
                
                // Si le compte est désactivé mais pas bloqué (blocage permanent)
                if (!$user->is_active && !$user->blocked_until) {
                    return redirect()->back()
                        ->withInput($request->only('email', 'role'))
                        ->withErrors([
                            'email' => 'Votre compte est désactivé. Contactez l\'administrateur.',
                        ]);
                }
            }
            
            // Si les tentatives dépassent le maximum, bloquer
            if ($failedAttempts >= $maxAttempts) {
                if (SettingsHelper::get('auto_block_enabled', 'true') === 'true') {
                    if ($user) {
                        $user->is_active = 0;
                        $user->blocked_until = now()->addMinutes($blockDuration);
                        $user->save();
                        
                        Log::warning('Compte bloqué (middleware)', [
                            'user' => $user->email,
                            'attempts' => $failedAttempts,
                            'duration' => $blockDuration . ' minutes',
                            'blocked_until' => $user->blocked_until,
                            'time' => now()
                        ]);
                        
                        return redirect()->back()
                            ->withInput($request->only('email', 'role'))
                            ->withErrors([
                                'email' => 'Trop de tentatives. Compte bloqué pour ' . $blockDuration . ' minutes.',
                            ]);
                    }
                }
            }
        }

        // ============================================
        // VÉRIFICATION DE L'UTILISATEUR CONNECTÉ
        // ============================================
        if (Auth::check()) {
            $user = Auth::user();
            
            // ============================================
            // 1. VÉRIFICATION DU BLOCAGE TEMPORAIRE
            // ============================================
            if ($user->blocked_until && $user->blocked_until->isFuture()) {
                $remaining = $user->getBlockedTimeRemaining();
                $this->logoutUser();
                return redirect('/login')->with('error', 'Compte bloqué. Réessayez dans ' . $remaining . '.');
            }
            
            // ============================================
            // 2. SI LE COMPTE EST DÉSACTIVÉ, LE RÉACTIVER SI LE BLOCAGE EST EXPIRÉ
            // ============================================
            if (!$user->is_active) {
                // Si blocked_until est passé ou null, réactiver le compte
                if (!$user->blocked_until || $user->blocked_until->isPast()) {
                    $user->is_active = 1;
                    $user->blocked_until = null;
                    $user->save();
                    
                    Log::info('Compte réactivé automatiquement', [
                        'user' => $user->email,
                        'time' => now()
                    ]);
                } else {
                    // Si blocked_until est dans le futur, c'est un blocage temporaire
                    $remaining = $user->getBlockedTimeRemaining();
                    $this->logoutUser();
                    return redirect('/login')->with('error', 'Compte bloqué. Réessayez dans ' . $remaining . '.');
                }
            }

            // ============================================
            // 3. RÉCUPÉRATION DE LA SESSION ACTUELLE
            // ============================================
            $currentSession = UserSession::where('session_token', session()->getId())
                ->where('user_id', $user->id)
                ->first();

            // ============================================
            // 4. VÉRIFICATION DE L'EXPIRATION DE SESSION
            // ============================================
            $sessionExpiry = (int) SettingsHelper::get('session_expiry', 60);
            
            if ($currentSession && $currentSession->last_activity) {
                $inactiveMinutes = $currentSession->last_activity->diffInMinutes(now());
                
                if ($inactiveMinutes > $sessionExpiry) {
                    $currentSession->endSession();
                    $this->logoutUser();
                    return redirect('/login')->with('error', 'Votre session a expiré.');
                }
            }

            // ============================================
            // 5. DÉCONNEXION AUTOMATIQUE APRÈS INACTIVITÉ
            // ============================================
            if (SettingsHelper::get('auto_logout_inactive', 'true') === 'true') {
                $inactivityTimeout = (int) SettingsHelper::get('inactivity_timeout', 15);
                
                if ($currentSession && $currentSession->last_activity) {
                    $inactiveMinutes = $currentSession->last_activity->diffInMinutes(now());
                    
                    if ($inactiveMinutes > $inactivityTimeout) {
                        $currentSession->endSession();
                        $this->logoutUser();
                        return redirect('/login')->with('error', 'Déconnecté pour cause d\'inactivité.');
                    }
                }
            }

            // ============================================
            // 6. SESSION UNIQUE
            // ============================================
            if (SettingsHelper::get('unique_session', 'true') === 'true') {
                UserSession::where('user_id', $user->id)
                    ->where('session_token', '!=', session()->getId())
                    ->where('is_current', true)
                    ->update([
                        'is_current' => false, 
                        'logout_time' => now()
                    ]);
            }

            // ============================================
            // 7. METTRE À JOUR L'ACTIVITÉ
            // ============================================
            if ($currentSession) {
                $currentSession->updateActivity();
            }
        }

        return $next($request);
    }

    private function logoutUser()
    {
        UserSession::where('session_token', session()->getId())
            ->update([
                'is_current' => false, 
                'logout_time' => now()
            ]);
        
        Auth::logout();
        Session::flush();
    }
}