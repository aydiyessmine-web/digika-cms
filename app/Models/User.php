<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Helper methods pour les rôles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    // Relations
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
}