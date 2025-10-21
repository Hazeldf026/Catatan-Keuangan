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
            CategorySeeder::class,
            RencanaSeeder::class,
        ]);

        $users = User::all();
        $pemasukanCategories = Category::where('tipe', 'pemasukan')->get();
        $pengeluaranCategories = Category::where('tipe', 'pengeluaran')->get();
        $targetUserEmail = 'hazeldf11@gmail.com';
        
        if ($users->isEmpty() || $pemasukanCategories->isEmpty() || $pengeluaranCategories->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            
            // --- LOGIKA BARU YANG LEBIH CERDAS UNTUK PENGGUNA TARGET ---
            if ($user->email === $targetUserEmail) {
                
                // 1. Inisialisasi pelacak saldo virtual untuk setiap media
                $mediaBalances = ['wallet' => 0, 'bank' => 0, 'e-wallet' => 0, 'tabungan' => 0];
                $totalPemasukan = 0;
                $totalPengeluaran = 0;
                $endDate = Carbon::now();

                // 2. FASE PEMASUKAN: Isi semua media dengan dana
                $incomeCount = 40; 
                for ($i = 0; $i < $incomeCount; $i++) {
                    $jumlah = rand(500000, 10000000); // Gaji atau pemasukan besar
                    $media = array_rand($mediaBalances); // Pilih media secara acak dari 4 pilihan
                    
                    Catatan::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $pemasukanCategories->random()->id,
                        'jumlah' => $jumlah,
                        'alokasi' => 'media',
                        'rencana_id' => null,
                        'media' => $media,
                        'created_at' => $endDate->copy()->subDays(rand(1, 365)),
                    ]);
                    $mediaBalances[$media] += $jumlah; // Update saldo virtual
                    $totalPemasukan += $jumlah;
                }

                // 3. FASE PENGELUARAN: Belanjakan 30-40% dari total pemasukan
                $targetExpense = $totalPemasukan * (rand(30, 40) / 100);
                while ($totalPengeluaran < $targetExpense) {
                    // Cari media yang masih punya saldo untuk belanja
                    $spendableMedia = array_keys(array_filter($mediaBalances, fn($balance) => $balance > 50000));
                    if (empty($spendableMedia)) break; // Berhenti jika tidak ada uang lagi
                    
                    $sourceMedia = $spendableMedia[array_rand($spendableMedia)];
                    $maxAmount = min(1500000, $mediaBalances[$sourceMedia]);
                    if ($maxAmount <= 10000) continue;
                    $jumlah = rand(10000, $maxAmount);
                    
                    Catatan::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $pengeluaranCategories->random()->id,
                        'jumlah' => $jumlah,
                        'alokasi' => null,
                        'rencana_id' => null,
                        'media' => $sourceMedia,
                        'created_at' => $endDate->copy()->subDays(rand(1, 365)),
                    ]);
                    
                    $mediaBalances[$sourceMedia] -= $jumlah; // Kurangi saldo virtual
                    $totalPengeluaran += $jumlah;
                }

                // 4. FASE ALOKASI RENCANA: Gunakan sisa uang di media untuk menabung
                $userRencanas = $user->rencanas()->where('status', 'berjalan')->get();
                $allocationCount = 20;
                for ($i = 0; $i < $allocationCount; $i++) {
                    $rencana = $userRencanas->first(fn($r) => $r->jumlah_terkumpul < $r->target_jumlah);
                    if (!$rencana) continue; // Semua rencana sudah selesai

                    // Cari media yang punya cukup uang untuk dialokasikan
                    $sourceMedia = null;
                    $jumlah = rand(100000, 1000000); // Jumlah tabungan per alokasi
                    foreach (array_keys($mediaBalances) as $media) {
                        if ($mediaBalances[$media] >= $jumlah) {
                            $sourceMedia = $media;
                            break;
                        }
                    }
                    if ($sourceMedia === null) continue; // Tidak ada media yang cukup uang

                    // Buat catatan "Pemasukan" yang dialokasikan ke Rencana
                    Catatan::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $pemasukanCategories->where('nama', 'Investasi')->first()->id ?? $pemasukanCategories->random()->id,
                        'jumlah' => $jumlah,
                        'alokasi' => 'rencana',
                        'rencana_id' => $rencana->id,
                        'media' => null,
                        'deskripsi' => 'Menabung untuk ' . $rencana->nama,
                        'created_at' => $endDate->copy()->subDays(rand(1, 365)),
                    ]);
                    
                    $mediaBalances[$sourceMedia] -= $jumlah; // Kurangi saldo virtual media
                    $rencana->increment('jumlah_terkumpul', $jumlah);
                    if ($rencana->fresh()->jumlah_terkumpul >= $rencana->target_jumlah) {
                        $rencana->update(['status' => 'selesai']);
                    }
                }

            } else { // Untuk user lain, gunakan factory biasa
                Catatan::factory(50)->for($user)->recycle(Category::all())->create();
            }
        }
    }
}
