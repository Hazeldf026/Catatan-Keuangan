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
        Schema::create('grup_rencanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_id')->constrained('grups')->onDelete('cascade'); // Relasi ke grup
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yg membuat rencana
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('target_jumlah', 15, 2);
            $table->decimal('jumlah_terkumpul', 15, 2)->default(0);
            $table->enum('status', ['berjalan', 'selesai', 'dibatalkan'])->default('berjalan');
            $table->boolean('is_pinned')->default(false); // Pinning jika masih relevan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_rencanas');
    }
};
