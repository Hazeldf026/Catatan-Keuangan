<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grup_catatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_id')->constrained('grups')->onDelete('cascade'); // Relasi ke grup
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang mencatat
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null'); // Kategori (bisa null)
            $table->string('custom_category')->nullable(); // Kategori custom jika 'Lainnya'
            $table->text('deskripsi');
            $table->decimal('jumlah', 15, 2); // Jumlah transaksi

            // Kolom dari 'catatans' personal (sesuaikan jika perlu)
            $table->enum('alokasi', ['rencana', 'media'])->nullable();
            // Relasi ke Rencana Grup (buat tabel grup_rencanas nanti)
            // $table->foreignId('grup_rencana_id')->nullable()->constrained('grup_rencanas')->onDelete('set null');
            $table->enum('media', ['wallet', 'bank', 'e-wallet', 'tabungan'])->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_catatans');
    }
};
