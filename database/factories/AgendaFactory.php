<?php

namespace Database\Factories;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(4),
            'tanggal_mulai' => fake()->dateTimeBetween('now', '+1 month'),
            'lokasi' => fake()->city(),
            'deskripsi' => fake()->paragraph(),
        ];
    }
}
