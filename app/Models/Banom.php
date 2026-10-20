<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Banom extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'singkatan',
        'deskripsi',
        'logo',
        'koordinator_id',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_stats_data');
            Cache::forget('site_banoms_data');
        });

        static::deleted(function () {
            Cache::forget('site_stats_data');
            Cache::forget('site_banoms_data');
        });
    }

    public function koordinator(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'koordinator_id');
    }

    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class, 'banom_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return static::resolveLogoUrl($this->logo, $this->singkatan);
    }

    /**
     * Resolve URL logo banom dengan urutan:
     * 1. URL eksternal (http/https)
     * 2. Berkas di storage/app/public/logo-organisasi/ (Storage::url)
     * 3. Berkas logo bawaan di public/images/banom/ (fallback file_exists)
     * 4. Lambang resmi NU di public/images/brand/logo-nu.png
     */
    public static function resolveLogoUrl(?string $logo, ?string $singkatan = null): string
    {
        if ($logo && (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://'))) {
            return $logo;
        }

        if ($logo) {
            $storageRelPath = str_starts_with($logo, 'logo-organisasi/') ? $logo : 'logo-organisasi/'.$logo;

            // Cek apakah berkas ada di storage disk public
            if (Storage::disk('public')->exists($storageRelPath)) {
                return asset('storage/'.$storageRelPath);
            }

            // Fallback ke public/images/banom/ jika di storage belum ada
            $baseName = basename($logo);
            $candidates = [
                $baseName,
                strtolower($baseName),
                strtoupper($baseName),
            ];

            foreach ($candidates as $cand) {
                if (file_exists(public_path('images/banom/'.$cand))) {
                    return asset('images/banom/'.$cand);
                }
            }
        }

        // Fallback berdasarkan singkatan
        if ($singkatan) {
            $clean = preg_replace('/[^a-zA-Z0-9_-]/', '', $singkatan);
            $candidates = [
                $clean.'.png',
                strtolower($clean).'.png',
                strtoupper($clean).'.png',
                $clean.'.svg',
            ];

            foreach ($candidates as $cand) {
                if (file_exists(public_path('images/banom/'.$cand))) {
                    return asset('images/banom/'.$cand);
                }
            }
        }

        return asset('images/brand/logo-nu.png');
    }
}
