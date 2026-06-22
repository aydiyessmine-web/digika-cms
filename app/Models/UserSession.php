<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';
    
    protected $fillable = [
        'user_id', 'session_token', 'ip_address', 'user_agent',
        'device', 'last_activity', 'is_current', 'login_time', 'logout_time'
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'last_activity' => 'datetime',
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function endSession()
    {
        $this->is_current = false;
        $this->logout_time = now();
        $this->save();
    }

    public function updateActivity()
    {
        $this->last_activity = now();
        $this->save();
    }

    public function isExpired($timeout = 60)
    {
        if (!$this->last_activity) return false;
        return $this->last_activity->diffInMinutes(now()) > $timeout;
    }

    // Formater la date pour l'affichage
    public function getFormattedLoginTime()
    {
        return $this->login_time ? $this->login_time->format('d/m/Y, H:i') : 'N/A';
    }

    public function getFormattedLastActivity()
    {
        return $this->last_activity ? $this->last_activity->format('d/m/Y, H:i') : 'N/A';
    }

    public function getStatusLabel()
    {
        if ($this->is_current) {
            return '<span class="status-success"><i class="fas fa-circle"></i> En cours</span>';
        }
        return '<span class="status-failed"><i class="fas fa-circle"></i> Terminé</span>';
    }
}