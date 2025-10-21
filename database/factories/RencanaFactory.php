<?php

namespace Database\Factories;

use App\Models\Rencana;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rencana>
 */
class RencanaFactory extends Factory
{
    protected $model = Rencana::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'nama' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(1),
            'target_jumlah' => $this->faker->randomElement([1000000, 5000000, 10000000, 20000000]),
            
            // PERUBAHAN: Selalu mulai dari nol
            'jumlah_terkumpul' => 0,
            'status' => 'berjalan',
            
            'target_tanggal' => $this->faker->dateTimeBetween('+2 months', '+2 years'),
        ];
    }
}
