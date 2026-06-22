<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group', 'key', 'value', 'description', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Récupérer une valeur par clé
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Récupérer tous les paramètres d'un groupe
    public static function getGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key');
    }

    // Mettre à jour ou créer un paramètre
    public static function set($key, $value, $group = 'general', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'description' => $description]
        );
    }
}