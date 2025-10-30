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
        Schema::table('grup_catatans', function (Blueprint $table) {
            $table->foreignId('grup_rencana_id')->nullable()->after('category_id')->constrained('grup_rencanas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grup_catatans', function (Blueprint $table) {
            $table->dropForeign(['rencana_id']);
            // Then drop the column
            $table->dropColumn(['rencana_id']);
        });
    }
};
