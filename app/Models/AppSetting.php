<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $guarded = [];
    protected $table = 'apps';
    protected $casts = [
        'analytics_scripts' => 'array',
    ];

    public static function current(): ?self
    {
        return Cache::rememberForever('app_settings', fn () => self::query()->first());
    }

    public static function refreshCache(): void
    {
        Cache::forget('app_settings');
        self::current(); // warm
    }



}
