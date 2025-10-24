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
        // Kita gunakan objek untuk melacak tanggal saat ini, 
        $dateTracker = (object)['current' => $endDate->copy()];

        return $this->state(new Sequence(
            function (Sequence $sequence) use ($dateTracker) {
                
                // Untuk item pertama (index 0), gunakan tanggal akhir
                if ($sequence->index > 0) {
                    // Untuk item selanjutnya, terapkan jeda acak
                    // 80% kemungkinan jeda 1 hari (jika rand 1-10 <= 8)
                    // 20% kemungkinan jeda 2 hari (jika rand 1-10 > 8)
                    $gap = (rand(1, 10) <= 8) ? 1 : 2; 
                    $dateTracker->current->subDays($gap);
                }

                return [
                    'created_at' => $dateTracker->current->copy(),
                    'updated_at' => $dateTracker->current->copy(),
                ];
            }
        )); 
    }
}
