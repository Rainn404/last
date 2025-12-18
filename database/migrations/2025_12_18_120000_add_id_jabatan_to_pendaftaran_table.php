<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Add id_jabatan column if it doesn't exist
            if (!Schema::hasColumn('pendaftaran', 'id_jabatan')) {
                $table->foreignId('id_jabatan')
                    ->nullable()
                    ->after('id_divisi')
                    ->constrained('jabatans', 'id_jabatan')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran', 'id_jabatan')) {
                $table->dropForeign(['id_jabatan']);
                $table->dropColumn('id_jabatan');
            }
        });
    }
};
