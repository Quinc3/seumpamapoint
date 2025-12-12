<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationSetting extends Model
{
    protected $fillable = ['resource_name', 'display_name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function isActive($resourceName)
    {
        $setting = static::where('resource_name', $resourceName)->first();
        return $setting ? $setting->is_active : true;
    }
}