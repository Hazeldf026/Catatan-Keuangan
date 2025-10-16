<?php

namespace Database\Seeders;

use App\Models\Rencana;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RencanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang ada
        $users = User::all();

        // Jika tidak ada user, hentikan seeder
        if ($users->isEmpty()) {
            $this->command->info('Tidak ada user ditemukan, RencanaSeeder dilewati.');
            return;
        }

        // Buat 5 rencana untuk setiap user
        foreach ($users as $user) {
            Rencana::factory()->count(6)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
