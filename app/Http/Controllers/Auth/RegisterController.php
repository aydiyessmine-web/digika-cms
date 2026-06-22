<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\SettingsHelper;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        // Récupérer la politique de mot de passe
        $policy = SettingsHelper::get('password_policy', 'basic');
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'role' => ['required', 'in:admin,marketing'],
        ];
        
        $messages = [];
        
        // Appliquer les règles selon la politique
        if ($policy === 'basic') {
            $rules['password'][] = 'min:8';
        } elseif ($policy === 'advanced') {
            $rules['password'][] = 'min:12';
            $rules['password'][] = 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/';
            $messages['password.regex'] = 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.';
        } elseif ($policy === 'strict') {
            $rules['password'][] = 'min:16';
            $rules['password'][] = 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{16,}$/';
            $messages['password.regex'] = 'Le mot de passe doit contenir au moins 16 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
        }
        
        return Validator::make($data, $rules, $messages);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => true,
        ]);
    }

    protected function registered($request, $user)
    {
        \App\Models\UserSession::create([
            'user_id' => $user->id,
            'session_token' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => $this->getDevice($request->userAgent()),
            'last_activity' => now(),
            'login_time' => now(),
            'is_current' => true,
        ]);

        return redirect()->intended($this->redirectTo);
    }

    private function getDevice($userAgent)
    {
        if (str_contains($userAgent, 'Mobile')) return 'Mobile';
        if (str_contains($userAgent, 'iPad')) return 'Tablet';
        return 'Desktop';
    }
}