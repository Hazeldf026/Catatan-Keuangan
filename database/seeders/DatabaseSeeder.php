<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Catatan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class
        ]);

        // =================================================================
        // LANGKAH 1: PERSIAPAN
        // =================================================================

        $users = User::all();
        // Ambil dan pisahkan kategori berdasarkan tipenya
        $pemasukanCategories = Category::where('tipe', 'pemasukan')->get();
        $pengeluaranCategories = Category::where('tipe', 'pengeluaran')->get();
        
        // Definisikan user target yang akan kita kontrol
        // Pastikan user dengan email ini ada di database (misal dari UserSeeder)
        $targetUserEmail = 'hazeldf11@gmail.com';
        
        if ($users->isEmpty() || $pemasukanCategories->isEmpty() || $pengeluaranCategories->isEmpty()) {
            return;
        }

        $recordsPerUser = 50;
        $endDate = Carbon::now();

        // =================================================================
        // LANGKAH 2: LOOPING DENGAN LOGIKA KONDISIONAL
        // =================================================================

        foreach ($users as $user) {
            
            // --- BLOK LOGIKA KHUSUS UNTUK USER TARGET ---
            if ($user->email === $targetUserEmail) {
                
                // Inisialisasi pelacak keuangan untuk user ini
                $totalPemasukan = 0;
                $totalPengeluaran = 0;

                // Loop untuk membuat catatan satu per satu
                for ($i = 0; $i < $recordsPerUser; $i++) {
                    
                    // Logika Cerdas: Tentukan tipe transaksi berikutnya
                    $currentRatio = ($totalPemasukan > 0) ? $totalPengeluaran / $totalPemasukan : 0;
                    
                    $isPemasukan = true; // Default-nya pemasukan

                    // Jika rasio masih di bawah 65% (buffer), kita beri kesempatan untuk pengeluaran
                    // Jika tidak, kita paksa jadi pemasukan untuk menyeimbangkan.
                    if ($currentRatio < 0.65) {
                        // 60% kemungkinan pemasukan, 40% kemungkinan pengeluaran
                        $isPemasukan = rand(1, 100) <= 60;
                    }

                    // Buat satu catatan dengan data yang sudah ditentukan
                    if ($isPemasukan) {
                        $jumlah = rand(50000, 1000000); // Pemasukan lebih besar
                        $category = $pemasukanCategories->random();
                        $totalPemasukan += $jumlah;
                    } else {
                        $jumlah = rand(10000, 300000); // Pengeluaran lebih kecil
                        $category = $pengeluaranCategories->random();
                        $totalPengeluaran += $jumlah;
                    }

                    Catatan::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $category->id,
                        'jumlah' => $jumlah,
                        'created_at' => $endDate->copy()->subDays($i),
                        'updated_at' => $endDate->copy()->subDays($i),
                    ]);
                }

            // --- BLOK LOGIKA LAMA UNTUK USER LAINNYA ---
            } else {
                Catatan::factory($recordsPerUser)
                    ->for($user)
                    // Untuk user lain, kategorinya acak (mix pemasukan & pengeluaran)
                    ->recycle(Category::all()) 
                    ->datesBackwardsFrom($endDate)
                    ->create();
            }
        }
    }
}
