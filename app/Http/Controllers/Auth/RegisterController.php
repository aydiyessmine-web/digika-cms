<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,marketing,viewer'],
        ]);
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

    // Après inscription réussie
    protected function registered($request, $user)
    {
        // Enregistrer la session
        \App\Models\UserSession::create([
            'user_id' => $user->id,
            'session_token' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => $this->getDevice($request->userAgent()),
            'last_activity' => now(),
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