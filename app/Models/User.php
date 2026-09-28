<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    const ROLE_ADMIN = 'admin';

    const ROLE_ANGGOTA = 'anggota';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function profilAnggota()
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    public function getOrBuildProfilAnggota()
    {
        if ($this->profilAnggota) {
            // Jika user adalah admin dan profilnya belum diset role-nya atau statusnya masih pending, auto-aktifkan
            if ($this->isAdmin() && (empty($this->profilAnggota->role) || $this->profilAnggota->status === 'Pending')) {
                $this->profilAnggota->role = 'Administrator';
                $this->profilAnggota->wilayah = $this->profilAnggota->wilayah ?: 'Pusat Cikembulan';
                $this->profilAnggota->status = 'Aktif';
                $this->profilAnggota->save();
            }

            return $this->profilAnggota;
        }

        return Anggota::create([
            'user_id' => $this->id,
            'name' => $this->name,
            'role' => $this->isAdmin() ? 'Administrator' : null,
            'wilayah' => $this->isAdmin() ? 'Pusat Cikembulan' : null,
            'status' => $this->isAdmin() ? 'Aktif' : 'Pending',
            'joined_at' => $this->created_at ?? now(),
            'is_public' => true,
        ]);
    }

    public function beritas()
    {
        return $this->hasMany(Berita::class, 'author_id');
    }
}
