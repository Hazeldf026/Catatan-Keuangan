<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Catatan>
 */
class CatatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'deskripsi' => $this->faker->sentence(),
            'jumlah' => $this->faker->numberBetween(10000, 500000),
            
            // PERUBAHAN BARU: Menambahkan default untuk kolom baru
            'alokasi' => null, 
            'rencana_id' => null,
            'media' => $this->faker->randomElement(['wallet', 'bank', 'e-wallet', 'tabungan']),
        ];
    }

    public function datesBackwardsFrom(Carbon $endDate): self
    {
        return $this->state(new Sequence(
            fn (Sequence $sequence) => [
                // Menggunakan subDays() untuk mundur ke belakang
                'created_at' => $endDate->copy()->subDays($sequence->index),
                'updated_at' => $endDate->copy()->subDays($sequence->index),
            ]
        ));
    }
}
