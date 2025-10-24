<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Catatan;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    private $faker;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Inisialisasi Faker
        $this->faker = Faker::create();

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
            
            // --- LOGIKA BARU UNTUK PENGGUNA TARGET (SESUAI ATURAN) ---
            if ($user->email === $targetUserEmail) {
                
                // 1. Inisialisasi pelacak saldo virtual dan tanggal
                $mediaBalances = ['wallet' => 0, 'bank' => 0, 'e-wallet' => 0, 'tabungan' => 0];
                $totalPemasukan = 0;
                $totalPengeluaran = 0;
                $currentDate = Carbon::now(); // Kursor tanggal

                // -----------------------------------------------------------------
                // FASE 1: PERENCANAAN KEUANGAN (Metode Akuntan)
                // -----------------------------------------------------------------
                
                // Tentukan Total Pemasukan Awal (misal 500jt)
                $totalTargetPemasukan = 200_000_000;
                
                // Tentukan Total Pengeluaran (30-40% dari Pemasukan, misal kita pakai 40%)
                $totalTargetPengeluaran = $totalTargetPemasukan * 0.40; // 200,000,000
                
                // Tentukan Saldo Akhir (sebelum alokasi Rencana)
                $targetSaldoAkhir = $totalTargetPemasukan - $totalTargetPengeluaran; // 300,000,000
                
                // ATURAN 1: Hitung target Saldo Akhir per media (80/10/8/2)
                $targetSaldoBank = $targetSaldoAkhir * 0.80;     // 240,000,000
                $targetSaldoTabungan = $targetSaldoAkhir * 0.10; // 30,000,000
                $targetSaldoEwallet = $targetSaldoAkhir * 0.08;  // 24,000,000
                $targetSaldoWallet = $targetSaldoAkhir * 0.02;   // 6,000,000

                // ATURAN 2: Hitung target Pengeluaran per sumber (Rasio 10:3)
                $totalParts = 13;
                $targetPengeluaranBank = ($totalTargetPengeluaran / $totalParts) * 10; // ~153,846,154
                $targetPengeluaranOther = ($totalTargetPengeluaran / $totalParts) * 3; // ~46,153,846

                // Hitung Target Pemasukan per media (Saldo Akhir + Pengeluaran)
                // Kita putuskan sumber "Other" (3) adalah dari 'tabungan' agar mudah dilacak
                $targetPemasukanBank = $targetSaldoBank + $targetPengeluaranBank;
                $targetPemasukanTabungan = $targetSaldoTabungan + $targetPengeluaranOther;
                $targetPemasukanEwallet = $targetSaldoEwallet; // Tidak ada pengeluaran
                $targetPemasukanWallet = $targetSaldoWallet; // Tidak ada pengeluaran

                // -----------------------------------------------------------------
                // FASE 2: SEEDING PEMASUKAN (untuk mencapai target Pemasukan)
                // -----------------------------------------------------------------
                
                // Helper function untuk membuat catatan pemasukan
                $createPemasukan = function($media, $targetJumlah) use ($user, $pemasukanCategories, &$mediaBalances, &$totalPemasukan, &$currentDate) {
                    while ($mediaBalances[$media] < $targetJumlah) {
                        $sisa = $targetJumlah - $mediaBalances[$media];
                        $jumlah = rand(min(1_000_000, $sisa), min(10_000_000, $sisa));
                        if ($jumlah <= 0) break;
                        
                        $gap = (rand(1, 10) <= 8) ? 1 : 2;
                        $currentDate->subDays($gap);

                        Catatan::factory()->create([
                            'user_id' => $user->id,
                            'category_id' => $pemasukanCategories->where('nama', 'Gaji')->first()->id ?? $pemasukanCategories->random()->id,
                            'jumlah' => $jumlah,
                            'alokasi' => 'media', 'rencana_id' => null, 'media' => $media,
                            'created_at' => $currentDate->copy(), 'updated_at' => $currentDate->copy(),
                        ]);
                        $mediaBalances[$media] += $jumlah;
                        $totalPemasukan += $jumlah;
                    }
                };

                // Jalankan seeder Pemasukan untuk setiap media
                $createPemasukan('bank', $targetPemasukanBank);
                $createPemasukan('tabungan', $targetPemasukanTabungan);
                $createPemasukan('e-wallet', $targetPemasukanEwallet);
                $createPemasukan('wallet', $targetPemasukanWallet);


                // -----------------------------------------------------------------
                // FASE 3: SEEDING PENGELUARAN (untuk mencapai target Pengeluaran 10:3)
                // -----------------------------------------------------------------
                
                $currentPengeluaranBank = 0;
                $currentPengeluaranOther = 0; // Dari Tabungan
                $bankExpenseRatio = $targetPengeluaranBank / $totalTargetPengeluaran; // ~0.769 (rasio 10/13)

                while ($currentPengeluaranBank < $targetPengeluaranBank || $currentPengeluaranOther < $targetPengeluaranOther) {
                    
                    // Tentukan apakah transaksi ini dari Bank atau Lainnya (Tabungan)
                    $isBankExpense = (rand(1, 1000) / 1000) < $bankExpenseRatio;

                    if ($isBankExpense && $currentPengeluaranBank < $targetPengeluaranBank) {
                        // --- BELANJA DARI BANK ---
                        $sourceMedia = 'bank';
                        $sisaTarget = $targetPengeluaranBank - $currentPengeluaranBank;
                        $maxAmount = min(1500000, $mediaBalances[$sourceMedia], $sisaTarget);
                        if ($maxAmount <= 10000) $maxAmount = min($mediaBalances[$sourceMedia], $sisaTarget); // Habiskan sisa
                        if ($maxAmount <= 0) continue;
                        
                        $jumlah = rand(10000, $maxAmount);
                        $currentPengeluaranBank += $jumlah;

                    } elseif (!$isBankExpense && $currentPengeluaranOther < $targetPengeluaranOther) {
                        // --- BELANJA DARI LAINNYA (TABUNGAN) ---
                        $sourceMedia = 'tabungan';
                        $sisaTarget = $targetPengeluaranOther - $currentPengeluaranOther;
                        $maxAmount = min(500000, $mediaBalances[$sourceMedia], $sisaTarget);
                        if ($maxAmount <= 10000) $maxAmount = min($mediaBalances[$sourceMedia], $sisaTarget); // Habiskan sisa
                        if ($maxAmount <= 0) continue;
                        
                        $jumlah = rand(10000, $maxAmount);
                        $currentPengeluaranOther += $jumlah;
                    
                    } else {
                        // Salah satu target sudah penuh, paksa ke target yg belum penuh
                        if ($currentPengeluaranBank < $targetPengeluaranBank) continue; // Ulangi loop, biarkan bank yg kena
                        if ($currentPengeluaranOther < $targetPengeluaranOther) continue; // Ulangi loop, biarkan other yg kena
                        break; // Keduanya penuh
                    }

                    // Terapkan jeda tanggal
                    $gap = (rand(1, 10) <= 8) ? 1 : 2;
                    $currentDate->subDays($gap);

                    // Buat catatan pengeluaran
                    Catatan::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $pengeluaranCategories->random()->id,
                        'jumlah' => $jumlah,
                        'alokasi' => null, 'rencana_id' => null, 'media' => $sourceMedia,
                        'created_at' => $currentDate->copy(), 'updated_at' => $currentDate->copy(),
                    ]);
                    
                    $mediaBalances[$sourceMedia] -= $jumlah; // Kurangi saldo virtual
                    $totalPengeluaran += $jumlah;
                }

                // -----------------------------------------------------------------
                // FASE 4: FASE ALOKASI RENCANA (Logika sama seperti sebelumnya)
                // -----------------------------------------------------------------
                $userRencanas = $user->rencanas()->where('status', 'berjalan')->get();
                if ($userRencanas->isEmpty()) continue; 

                $allocationCount = 20; // Jumlah alokasi tabungan
                for ($i = 0; $i < $allocationCount; $i++) {
                    $rencana = $userRencanas->first(fn($r) => $r->jumlah_terkumpul < $r->target_jumlah);
                    if (!$rencana) break; 

                    $jumlah = rand(100000, 1000000);
                    
                    // Cari media yang punya cukup uang (tidak akan minus)
                    $sourceMedia = null;
                    // Urutkan prioritas media pencarian (Bank dulu, karena saldonya paling banyak)
                    $priorityMedia = ['bank', 'tabungan', 'e-wallet', 'wallet'];
                    foreach ($priorityMedia as $media) {
                        if ($mediaBalances[$media] >= $jumlah) {
                            $sourceMedia = $media;
                            break;
                        }
                    }
                    if ($sourceMedia === null) continue; // Tidak ada media yang cukup uang

                    // Terapkan jeda tanggal
                    $gap = (rand(1, 10) <= 8) ? 1 : 2;
                    $currentDate->subDays($gap);

                    Catatan::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $pemasukanCategories->where('nama', 'Investasi')->first()->id ?? $pemasukanCategories->random()->id,
                        'jumlah' => $jumlah,
                        'alokasi' => 'rencana', 'rencana_id' => $rencana->id, 'media' => null,
                        'deskripsi' => 'Menabung untuk ' . $rencana->nama,
                        'created_at' => $currentDate->copy(), 'updated_at' => $currentDate->copy(),
                    ]);
                    
                    $mediaBalances[$sourceMedia] -= $jumlah; // Kurangi saldo virtual media
                    $rencana->increment('jumlah_terkumpul', $jumlah);
                    
                    if ($rencana->fresh()->jumlah_terkumpul >= $rencana->target_jumlah) {
                        $rencana->update(['status' => 'selesai']);
                    }
                }

            } else { 
                // --- LOGIKA LAMA (UNTUK USER LAIN) ---
                $jumlahCatatan = 50;
                Catatan::factory($jumlahCatatan)
                    ->for($user)
                    ->recycle(Category::all())
                    ->datesBackwardsFrom(Carbon::now()) // Terapkan logika tanggal
                    ->create();
            }
        }
    }
}
