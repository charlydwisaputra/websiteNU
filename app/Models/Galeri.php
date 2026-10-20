<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Galeri extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'photo',
        'category',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_stats_data');
        });

        static::deleted(function () {
            Cache::forget('site_stats_data');
        });
    }
}
