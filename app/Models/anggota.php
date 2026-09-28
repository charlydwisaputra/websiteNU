<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $guarded = ['id'];

    protected $appends = ['role', 'wilayah'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRoleAttribute()
    {
        return $this->attributes['position'] ?? null;
    }

    public function setRoleAttribute($value)
    {
        $this->attributes['position'] = $value;
    }

    public function getWilayahAttribute()
    {
        return $this->attributes['region'] ?? null;
    }

    public function setWilayahAttribute($value)
    {
        $this->attributes['region'] = $value;
    }
}
