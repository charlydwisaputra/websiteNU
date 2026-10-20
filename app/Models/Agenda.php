<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

    protected $fillable = [
        'judul',
        'tanggal_mulai',
        'lokasi',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
        ];
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
