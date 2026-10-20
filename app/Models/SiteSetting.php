<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_stats_data');
            Cache::forget('site_settings_all');
        });

        static::deleted(function () {
            Cache::forget('site_stats_data');
            Cache::forget('site_settings_all');
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('site_settings_all', 300, function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): static
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
        );

        Cache::forget('site_settings_all');
        Cache::forget('site_stats_data');

        return $setting;
    }
}
