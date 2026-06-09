<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['title', 'type', 'file_path', 'thumbnail_path', 'size', 'duration', 'resolution', 'user_id', 'category_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function campaigns() { return $this->belongsToMany(Campaign::class, 'campaign_media')->withPivot('position', 'duration'); }
}