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
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'custom_category' => fake()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'jumlah' => fake()->numberBetween(10000, 1000000),
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
