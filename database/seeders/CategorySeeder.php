<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat beberapa Kategori Pemasukan
        Category::factory()->create(['nama' => 'Gaji', 'tipe' => 'pemasukan']);
        Category::factory()->create(['nama' => 'Bonus', 'tipe' => 'pemasukan']);
        Category::factory()->create(['nama' => 'Usaha', 'tipe' => 'pemasukan']);
        Category::factory()->create(['nama' => 'Investasi', 'tipe' => 'pemasukan']);
        Category::factory()->create(['nama' => 'Lainnya...', 'tipe' => 'pemasukan']);

        // Buat beberapa Kategori Pengeluaran
        Category::factory()->create(['nama' => 'Makanan & Minuman', 'tipe' => 'pengeluaran']);
        Category::factory()->create(['nama' => 'Transportasi', 'tipe' => 'pengeluaran']);
        Category::factory()->create(['nama' => 'Tagihan', 'tipe' => 'pengeluaran']);
        Category::factory()->create(['nama' => 'Hiburan', 'tipe' => 'pengeluaran']);
        Category::factory()->create(['nama' => 'Lainnya...', 'tipe' => 'pengeluaran']);
    }
}
