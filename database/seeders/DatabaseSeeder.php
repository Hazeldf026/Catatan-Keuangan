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
        // =================================================================
        // LANGKAH 1: MENJALANKAN SEEDER DASAR
        // =================================================================
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            RencanaSeeder::class, // <-- Pastikan RencanaSeeder dipanggil di sini
        ]);

        // =================================================================
        // LANGKAH 2: PERSIAPAN DATA UNTUK CATATAN
        // =================================================================
        $users = User::all();
        $pemasukanCategories = Category::where('tipe', 'pemasukan')->get();
        $pengeluaranCategories = Category::where('tipe', 'pengeluaran')->get();
        
        $targetUserEmail = 'hazeldf11@gmail.com';
        
        if ($users->isEmpty() || $pemasukanCategories->isEmpty() || $pengeluaranCategories->isEmpty()) {
            return;
        }

        $recordsPerUser = 50;
        $endDate = Carbon::now();

        // =================================================================
        // LANGKAH 3: LOOPING UNTUK MEMBUAT CATATAN DENGAN KONEKSI KE RENCANA
        // =================================================================
        foreach ($users as $user) {
            
            // --- BLOK LOGIKA KHUSUS UNTUK USER TARGET ---
            if ($user->email === $targetUserEmail) {
                
                // Ambil semua rencana 'berjalan' milik user target
                $userRencanas = $user->rencanas()->where('status', 'berjalan')->get();

                $totalPemasukan = 0;
                $totalPengeluaran = 0;

                for ($i = 0; $i < $recordsPerUser; $i++) {
                    
                    $currentRatio = ($totalPemasukan > 0) ? $totalPengeluaran / $totalPemasukan : 0;
                    $isPemasukan = ($currentRatio < 0.65) ? (rand(1, 100) <= 60) : true;

                    $attributes = [
                        'user_id' => $user->id,
                        'created_at' => $endDate->copy()->subDays($i),
                        'updated_at' => $endDate->copy()->subDays($i),
                    ];

                    if ($isPemasukan) {
                        $jumlah = rand(50000, 1000000);
                        $attributes['category_id'] = $pemasukanCategories->random()->id;
                        $attributes['jumlah'] = $jumlah;
                        $totalPemasukan += $jumlah;

                        // === LOGIKA BARU UNTUK MENGHUBUNGKAN CATATAN KE RENCANA ===
                        // 25% kemungkinan pemasukan ini akan dialokasikan ke rencana
                        if (!$userRencanas->isEmpty() && rand(1, 100) <= 25) {
                            $rencana = $userRencanas->random();

                            // Hanya alokasikan jika rencana belum selesai
                            if ($rencana->jumlah_terkumpul < $rencana->target_jumlah) {
                                $attributes['alokasi'] = 'rencana';
                                $attributes['rencana_id'] = $rencana->id;

                                // Update jumlah terkumpul di rencana
                                $rencana->increment('jumlah_terkumpul', $jumlah);

                                // Cek apakah rencana sudah selesai setelah ditambah
                                if ($rencana->fresh()->jumlah_terkumpul >= $rencana->target_jumlah) {
                                    $rencana->update(['status' => 'selesai']);
                                }
                            }
                        }
                        // ========================================================
                        
                    } else { // Jika Pengeluaran
                        $jumlah = rand(10000, 300000);
                        $attributes['category_id'] = $pengeluaranCategories->random()->id;
                        $attributes['jumlah'] = $jumlah;
                        $totalPengeluaran += $jumlah;
                    }

                    // Buat catatan dengan atribut yang sudah ditentukan
                    Catatan::factory()->create($attributes);
                }

            // --- BLOK LOGIKA UNTUK USER LAINNYA ---
            } else {
                Catatan::factory($recordsPerUser)
                    ->for($user)
                    ->recycle(Category::all())
                    ->create(); // Membuat 50 catatan acak untuk user lain
            }
        }
    }
}
