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
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Tambahkan kolom wa_sent jika belum ada
            if (!Schema::hasColumn('pendaftaran', 'wa_sent')) {
                $table->boolean('wa_sent')->default(false)->after('validated_at')
                    ->comment('Flag untuk tracking apakah WhatsApp notifikasi sudah dikirim');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('wa_sent');
        });
    }
};
