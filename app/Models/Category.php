<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'keywords', 'user_id', 'media_count'];
    
    protected $casts = [
        'keywords' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function media()
    {
        return $this->hasMany(Media::class);
    }
}