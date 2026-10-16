<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
