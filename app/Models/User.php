<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 
        'last_login_at', 'last_login_ip', 'blocked_until'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'blocked_until' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    // Vérifier si le compte est bloqué temporairement
    public function isBlocked(): bool
    {
        if (!$this->blocked_until) return false;
        return $this->blocked_until->isFuture();
    }

    // Ajouter cette méthode dans le modèle User
    public function getBlockedTimeRemaining(): ?string
    {
        if (!$this->blocked_until || !$this->blocked_until->isFuture()) {
            return null;
        }
        
        $minutes = $this->blocked_until->diffInMinutes(now());
        $seconds = $this->blocked_until->diffInSeconds(now()) % 60;
        
        if ($minutes > 0) {
            return $minutes . ' min ' . $seconds . ' s';
        }
        return $seconds . ' secondes';
    }

    // Débloquer le compte
    public function unblock()
    {
        $this->blocked_until = null;
        $this->is_active = true;
        $this->save();
    }

    // Bloquer le compte pour une durée donnée (en minutes)
    public function block($minutes = 15)
    {
        $this->blocked_until = now()->addMinutes($minutes);
        $this->is_active = false;
        $this->save();
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function aiRequests()
    {
        return $this->hasMany(AiRequest::class);
    }

    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }
    protected $appends = ['status_label'];

    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Actif' : 'Inactif';
    }

    public function getStatusClassAttribute()
    {
        return $this->is_active ? 'active' : 'inactive';
    }
}