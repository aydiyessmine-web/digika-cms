<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRequest extends Model
{
    protected $fillable = ['user_id', 'type', 'prompt', 'response', 'tokens_used', 'duration_ms'];

    public function user() { return $this->belongsTo(User::class); }
}