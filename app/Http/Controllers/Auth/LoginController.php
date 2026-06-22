<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Helpers\SettingsHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';
    protected $maxAttempts = 5;
    protected $decayMinutes = 15;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->maxAttempts = (int) SettingsHelper::get('login_attempts', 5);
        $this->decayMinutes = (int) SettingsHelper::get('block_duration', 15);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password' => 'required|string',
            'role' => 'required|in:admin,marketing',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $credentials['role'] = $request->input('role');
        
        return $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );
    }

    protected function authenticated(Request $request, $user)
    {
        // ============================================
        // RÉINITIALISER TOUT AVANT DE CONTINUER
        // ============================================
        // Réinitialiser les tentatives
        session(['failed_login_attempts' => 0]);
        
        // Réactiver le compte et enlever le blocage
        $user->is_active = 1;
        $user->blocked_until = null;
        $user->save();

        // Vérifier si le compte est actif (après réactivation)
        if (!$user->is_active) {
            $this->guard()->logout();
            return redirect('/login')->withErrors([
                'email' => 'Votre compte est désactivé. Contactez l\'administrateur.',
            ]);
        }

        // Session unique
        if (SettingsHelper::get('unique_session', 'true') === 'true') {
            UserSession::where('user_id', $user->id)
                ->where('session_token', '!=', session()->getId())
                ->where('is_current', true)
                ->update([
                    'is_current' => false, 
                    'logout_time' => now()
                ]);
        }

        // Créer la session
        UserSession::updateOrCreate(
            ['session_token' => session()->getId()],
            [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device' => $this->getDevice($request->userAgent()),
                'last_activity' => now(),
                'login_time' => now(),
                'logout_time' => null,
                'is_current' => true,
            ]
        );

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        if (SettingsHelper::get('log_all_attempts', 'true') === 'true') {
            Log::info('Connexion réussie', [
                'user' => $user->email,
                'ip' => $request->ip(),
                'time' => now()
            ]);
        }

        return redirect()->intended($this->redirectTo);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $attempts = session('failed_login_attempts', 0) + 1;
        session(['failed_login_attempts' => $attempts]);
        session(['last_failed_attempt' => now()]);
        
        $maxAttempts = (int) SettingsHelper::get('login_attempts', 5);
        $blockDuration = (int) SettingsHelper::get('block_duration', 15);
        
        // Vérifier si le compte doit être bloqué
        if ($attempts >= $maxAttempts) {
            if (SettingsHelper::get('auto_block_enabled', 'true') === 'true') {
                $user = \App\Models\User::where('email', $request->email)->first();
                if ($user) {
                    // Bloquer le compte
                    $user->is_active = 0;
                    $user->blocked_until = now()->addMinutes($blockDuration);
                    $user->save();
                    
                    Log::warning('Compte bloqué', [
                        'user' => $user->email,
                        'attempts' => $attempts,
                        'duration' => $blockDuration . ' minutes',
                        'blocked_until' => $user->blocked_until,
                        'time' => now()
                    ]);
                    
                    return redirect()->back()
                        ->withInput($request->only('email', 'role'))
                        ->withErrors([
                            'email' => 'Compte bloqué pour ' . $blockDuration . ' minutes après ' . $attempts . ' tentatives échouées.',
                        ]);
                }
            }
        }

        if (SettingsHelper::get('log_all_attempts', 'true') === 'true') {
            Log::warning('Tentative de connexion échouée', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'attempts' => $attempts,
                'max' => $maxAttempts,
                'time' => now()
            ]);
        }

        $remaining = $maxAttempts - $attempts;
        $message = 'Identifiants incorrects';
        
        if ($remaining > 0) {
            $message .= ' - ' . $remaining . ' tentative' . ($remaining > 1 ? 's' : '') . ' restante' . ($remaining > 1 ? 's' : '');
        } else {
            $message .= ' - Compte bloqué pour ' . $blockDuration . ' minutes';
        }

        return redirect()->back()
            ->withInput($request->only('email', 'role', 'remember'))
            ->withErrors([
                'email' => $message,
            ]);
    }

    public function logout(Request $request)
    {
        UserSession::where('session_token', session()->getId())
            ->update([
                'is_current' => false, 
                'logout_time' => now()
            ]);

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function getDevice($userAgent)
    {
        if (str_contains($userAgent, 'Mobile')) return 'Mobile';
        if (str_contains($userAgent, 'iPad')) return 'Tablet';
        return 'Desktop';
    }
}