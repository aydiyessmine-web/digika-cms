<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'status', 'progress', 'media_count', 'screens_count', 'user_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function media() { return $this->belongsToMany(Media::class, 'campaign_media')->withPivot('position', 'duration'); }
    public function screens() { return $this->belongsToMany(Screen::class, 'screen_campaign')->withPivot('start_date', 'end_date', 'is_active'); }
}