<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seeder khusus Admin dan mematikan factory default bawaan Laravel
        $this->call([
            AdminSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
