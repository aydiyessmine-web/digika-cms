<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Ajouter la validation du rôle dans la requête
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password' => 'required|string',
            'role' => 'required|in:admin,marketing,viewer',
        ]);
    }

    // Tentative de connexion avec vérification du rôle
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $credentials['role'] = $request->input('role');
        
        return $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );
    }

    // Après connexion réussie
    protected function authenticated(Request $request, $user)
    {
        // Vérifier si compte actif
        if (!$user->is_active) {
            $this->guard()->logout();
            return redirect('/login')->withErrors([
                'email' => 'Votre compte est désactivé. Contactez l\'administrateur.',
            ]);
        }

        // Mettre à jour la dernière connexion
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Créer une session active
        \App\Models\UserSession::updateOrCreate(
            ['session_token' => session()->getId()],
            [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device' => $this->getDevice($request->userAgent()),
                'last_activity' => now(),
                'is_current' => true,
            ]
        );

        return redirect()->intended($this->redirectTo);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        // Marquer la session comme inactive
        \App\Models\UserSession::where('session_token', session()->getId())
            ->update(['is_current' => false]);

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