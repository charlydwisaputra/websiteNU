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
        'category',
        'description',
        'photo',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    /**
     * Foto cover (foto pertama dari array, atau kolom photo lama).
     */
    public function getCoverAttribute(): ?string
    {
        $photos = $this->photos ?? [];

        return $photos[0] ?? $this->photo ?? null;
    }

    /**
     * Semua foto dalam album (gabungan photos + photo lama).
     *
     * @return string[]
     */
    public function getAllPhotosAttribute(): array
    {
        return $this->photos ?? ($this->photo ? [$this->photo] : []);
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
}
