<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $fillable = [
        'title',
        'category',
        'content',
        'status',
        'image',
        'author_id',
    ];

    protected $appends = ['reading_time'];

    public function getReadingTimeAttribute(): string
    {
        $words = str_word_count(strip_tags($this->content ?? ''));
        $minutes = max(1, (int) ceil($words / 180));

        return "{$minutes} menit baca";
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_stats_data');
        });

        static::deleted(function () {
            Cache::forget('site_stats_data');
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
