<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    protected $fillable = [
        'route_name',
        'slug',
        'title',
        'h1',
        'meta_description',
        'meta_keywords',
        'banner_image',
        'settings',
        'is_active'
    ];

    protected $casts = ['settings' => 'array', 'is_active' => 'boolean'];

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image ? Storage::disk('public')->url($this->banner_image) : null;
    }

    public static function forCurrentRoute(): ?self
    {
        $route = optional(request()->route())->getName();
        return $route ? static::where('route_name', $route)->first() : null;
    }
}
