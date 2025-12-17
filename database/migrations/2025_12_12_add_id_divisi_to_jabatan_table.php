<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            if (!Schema::hasColumn('jabatans', 'id_divisi')) {
                $table->foreignId('id_divisi')->nullable()->constrained('divisis', 'id_divisi')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            if (Schema::hasColumn('jabatans', 'id_divisi')) {
                $table->dropForeignKey(['id_divisi']);
                $table->dropColumn('id_divisi');
            }
        });
    }
};
