<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'status', 'start_date', 'end_date',
        'progress', 'media_count', 'background_image'
    ];

    // علاقة many-to-many مع جدول media
    public function media()
    {
        return $this->belongsToMany(Media::class, 'campaign_media')
                    ->withPivot('position')
                    ->withTimestamps()
                    ->orderBy('pivot_position');
    }
}