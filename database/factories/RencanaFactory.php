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
        $target = $this->faker->numberBetween(1000000, 20000000);
        $terkumpul = $this->faker->numberBetween(0, $target);
        $status = ($terkumpul >= $target) ? 'selesai' : 'berjalan';

        return [
            // Pilih user secara acak dari yang sudah ada
            'user_id' => User::inRandomOrder()->first()->id,
            'nama' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(2),
            'target_jumlah' => $this->faker->numberBetween(1000000, 20000000),
            'jumlah_terkumpul' => 0,
            'status' => 'berjalan', // Selalu 'berjalan' saat dibuat
            
            'target_tanggal' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}
