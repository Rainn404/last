<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Status column sudah enum, hanya set semua menjadi active
        DB::statement("UPDATE divisis SET status = 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('divisis', function (Blueprint $table) {
            // Revert back to boolean
            DB::statement("ALTER TABLE divisis MODIFY status BOOLEAN DEFAULT true");
        });
    }
};
