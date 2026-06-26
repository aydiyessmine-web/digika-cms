<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSession;
use App\Helpers\SettingsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        $totalUsers = $users->count();
        $activeUsers = $users->where('is_active', true)->count();
        $adminUsers = $users->where('role', 'admin')->count();
        $pendingInvitations = 0;
        
        return view('profiles.index', compact(
            'users', 'totalUsers', 'activeUsers', 'adminUsers', 'pendingInvitations'
        ));
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,marketing',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->is_active ?? true,
        ]);

        Log::info('Nouvel utilisateur créé', [
            'user' => Auth::user()->email,
            'new_user' => $user->email,
            'role' => $user->role,
            'time' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,marketing',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active ?? $user->is_active,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        Log::info('Utilisateur modifié', [
            'user' => Auth::user()->email,
            'updated_user' => $user->email,
            'time' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur modifié avec succès',
            'user' => $user
        ]);
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas désactiver votre propre compte'
            ], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        
        Log::info('Compte utilisateur ' . $status, [
            'user' => Auth::user()->email,
            'target_user' => $user->email,
            'time' => now()
        ]);

        if (!$user->is_active) {
            UserSession::where('user_id', $user->id)
                ->where('is_current', true)
                ->update([
                    'is_current' => false, 
                    'logout_time' => now()
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Compte ' . $status . ' avec succès',
            'is_active' => $user->is_active
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte'
            ], 403);
        }

        UserSession::where('user_id', $user->id)->delete();
        
        $email = $user->email;
        $user->delete();

        Log::warning('Utilisateur supprimé', [
            'user' => Auth::user()->email,
            'deleted_user' => $email,
            'time' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    public function export()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        $csv = "Nom,Email,Rôle,Statut,Dernière connexion,Date création\n";
        foreach ($users as $user) {
            $status = $user->is_active ? 'Actif' : 'Inactif';
            $lastLogin = $user->last_login_at ? $user->last_login_at->format('d/m/Y, H:i') : 'Jamais';
            $csv .= $user->name . ","
                    . $user->email . ","
                    . $this->getRoleLabel($user->role) . ","
                    . $status . ","
                    . $lastLogin . ","
                    . $user->created_at->format('d/m/Y, H:i') . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="utilisateurs_' . date('Y-m-d') . '.csv"');
    }

    private function getRoleLabel($role)
    {
        $roles = [
            'admin' => 'Administrateur',
            'marketing' => 'Marketing',
        ];
        return $roles[$role] ?? ucfirst($role);
    }
}