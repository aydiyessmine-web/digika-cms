<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    protected $fillable = ['name', 'location', 'resolution', 'status', 'last_seen', 'user_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function campaigns() { return $this->belongsToMany(Campaign::class, 'screen_campaign')->withPivot('start_date', 'end_date', 'is_active'); }
}