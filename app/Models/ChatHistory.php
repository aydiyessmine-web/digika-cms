<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    protected $table = 'chat_history';
    
    protected $fillable = [
        'user_id', 'session_id', 'role', 'content'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->session_id) {
                $model->session_id = session()->getId();
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}