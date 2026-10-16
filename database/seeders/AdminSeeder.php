<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrCreate untuk mencegah duplikasi jika seeder dijalankan ulang
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Gunakan email yang aman untuk env Anda
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'), // Ingat, jangan hardcode di production sesungguhnya
                'role' => User::ROLE_ADMIN, // Mengambil konstan dari model User
            ]
        );
    }
}
