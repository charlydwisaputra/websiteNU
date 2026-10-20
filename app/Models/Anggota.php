<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'position',
        'banom_id',
        'region',
        'tampil_wilayah',
        'status',
        'kartu_token',
        'joined_at',
        'bio',
        'is_public',
        'urutan',
        'is_pengurus',
    ];

    protected $appends = ['role', 'wilayah'];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_pengurus' => 'boolean',
            'tampil_wilayah' => 'boolean',
            'urutan' => 'integer',
            'joined_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($anggota) {
            if (empty($anggota->kartu_token)) {
                $anggota->kartu_token = Str::random(32);
            }
        });

        static::saved(function () {
            Cache::forget('site_stats_data');
            Cache::forget('site_pengurus_data');
            Cache::forget('site_banoms_data');
        });

        static::deleted(function () {
            Cache::forget('site_stats_data');
            Cache::forget('site_pengurus_data');
            Cache::forget('site_banoms_data');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banom(): BelongsTo
    {
        return $this->belongsTo(Banom::class, 'banom_id');
    }

    public function coordinatedBanom(): HasOne
    {
        return $this->hasOne(Banom::class, 'koordinator_id');
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['position'] ?? null;
    }

    public function setRoleAttribute(?string $value): void
    {
        $this->attributes['position'] = $value;
    }

    public function getWilayahAttribute(): ?string
    {
        return $this->attributes['region'] ?? null;
    }

    public function setWilayahAttribute(?string $value): void
    {
        $this->attributes['region'] = $value;
    }

    public function getJabatanLengkapAttribute(): string
    {
        $pos = trim($this->position ?? '');
        $banomName = $this->banom ? $this->banom->singkatan : null;

        if ($pos === '') {
            return $banomName ? "Anggota {$banomName}" : 'Anggota';
        }

        $posLower = strtolower($pos);

        if ($posLower === 'ketua') {
            if ($banomName) {
                return "Ketua {$banomName}";
            }
            if ($this->is_pengurus) {
                return 'Ketua Tanfidziyah';
            }

            return 'Ketua Pengurus Ranting';
        }

        if ($posLower === 'wakil ketua') {
            if ($banomName) {
                return "Wakil Ketua {$banomName}";
            }

            return 'Wakil Ketua Pengurus Ranting';
        }

        if ($posLower === 'sekretaris') {
            if ($banomName) {
                return "Sekretaris {$banomName}";
            }

            return 'Sekretaris Pengurus Ranting';
        }

        if ($posLower === 'bendahara') {
            if ($banomName) {
                return "Bendahara {$banomName}";
            }

            return 'Bendahara Pengurus Ranting';
        }

        if ($posLower === 'wakil sekretaris') {
            return 'Wakil Sekretaris Pengurus Ranting';
        }

        if ($posLower === 'wakil bendahara') {
            return 'Wakil Bendahara Pengurus Ranting';
        }

        return $pos;
    }

    /**
     * @return array{label: string, class: string, bg: string, text: string, border: string}
     */
    public function getBadgeAttribute(): array
    {
        if ($this->banom) {
            $singkatan = $this->banom->singkatan ?: $this->banom->nama;
            $slug = strtolower(trim($singkatan));

            $themeStyles = match ($slug) {
                'muslimat' => [
                    'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700/50',
                    'bg' => 'rgba(16, 185, 129, 0.12)',
                    'text' => '#047857',
                    'border' => 'rgba(16, 185, 129, 0.25)',
                ],
                'fatayat' => [
                    'class' => 'bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border-teal-200 dark:border-teal-700/50',
                    'bg' => 'rgba(20, 184, 166, 0.12)',
                    'text' => '#0d9488',
                    'border' => 'rgba(20, 184, 166, 0.25)',
                ],
                'gp ansor', 'ansor' => [
                    'class' => 'bg-green-50 dark:bg-green-950/60 text-green-800 dark:text-green-300 border-green-200 dark:border-green-600/50',
                    'bg' => 'rgba(34, 197, 94, 0.12)',
                    'text' => '#15803d',
                    'border' => 'rgba(34, 197, 94, 0.25)',
                ],
                'ipnu' => [
                    'class' => 'bg-sky-50 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300 border-sky-200 dark:border-sky-700/50',
                    'bg' => 'rgba(14, 165, 233, 0.12)',
                    'text' => '#0284c7',
                    'border' => 'rgba(14, 165, 233, 0.25)',
                ],
                'ippnu' => [
                    'class' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 border-indigo-200 dark:border-indigo-700/50',
                    'bg' => 'rgba(99, 102, 241, 0.12)',
                    'text' => '#4f46e5',
                    'border' => 'rgba(99, 102, 241, 0.25)',
                ],
                'pmii' => [
                    'class' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border-amber-200 dark:border-amber-700/50',
                    'bg' => 'rgba(245, 158, 11, 0.12)',
                    'text' => '#b45309',
                    'border' => 'rgba(245, 158, 11, 0.25)',
                ],
                default => [
                    'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700/50',
                    'bg' => 'rgba(16, 185, 129, 0.12)',
                    'text' => '#047857',
                    'border' => 'rgba(16, 185, 129, 0.25)',
                ],
            };

            return array_merge(['label' => $singkatan], $themeStyles);
        }

        // Anggota Pengurus Ranting yang tidak punya badan otonom tetap diberi badge "Pengurus Ranting"
        return [
            'label' => 'Pengurus Ranting',
            'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700/50',
            'bg' => 'rgba(16, 185, 129, 0.12)',
            'text' => '#047857',
            'border' => 'rgba(16, 185, 129, 0.25)',
        ];
    }

    /**
     * @return array{bg: string, text: string, border: string}
     */
    public function getAvatarColorAttribute(): array
    {
        $palettes = [
            ['bg' => 'rgba(16, 185, 129, 0.15)', 'text' => '#10b981', 'border' => 'rgba(16, 185, 129, 0.3)'],
            ['bg' => 'rgba(14, 165, 233, 0.15)', 'text' => '#0ea5e9', 'border' => 'rgba(14, 165, 233, 0.3)'],
            ['bg' => 'rgba(245, 158, 11, 0.15)', 'text' => '#f59e0b', 'border' => 'rgba(245, 158, 11, 0.3)'],
            ['bg' => 'rgba(139, 92, 246, 0.15)', 'text' => '#8b5cf6', 'border' => 'rgba(139, 92, 246, 0.3)'],
            ['bg' => 'rgba(236, 72, 153, 0.15)', 'text' => '#ec4899', 'border' => 'rgba(236, 72, 153, 0.3)'],
            ['bg' => 'rgba(20, 184, 166, 0.15)', 'text' => '#14b8a6', 'border' => 'rgba(20, 184, 166, 0.3)'],
            ['bg' => 'rgba(249, 115, 22, 0.15)', 'text' => '#f97316', 'border' => 'rgba(249, 115, 22, 0.3)'],
            ['bg' => 'rgba(99, 102, 241, 0.15)', 'text' => '#6366f1', 'border' => 'rgba(99, 102, 241, 0.3)'],
        ];

        $hash = abs(crc32($this->name ?: 'NU'));

        return $palettes[$hash % count($palettes)];
    }
}
