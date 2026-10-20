<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'photo' => null,
            'position' => 'Anggota',
            'region' => 'RT 01 / RW 01 - Kalisari',
            'status' => 'Aktif',
            'kartu_token' => Str::random(32),
            'joined_at' => now(),
            'bio' => fake()->paragraph(),
            'is_public' => true,
            'is_pengurus' => false,
            'urutan' => 0,
        ];
    }
}
