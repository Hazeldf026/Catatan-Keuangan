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
        Schema::table('catatans', function (Blueprint $table) {
            $table->enum('alokasi', ['rencana', 'simpanan'])->nullable()->after('category_id');
            $table->foreignId('rencana_id')->nullable()->after('category_id')->constrained('rencanas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['rencana_id']);
            // Then drop the column
            $table->dropColumn(['alokasi', 'rencana_id']);
        });
    }
};
