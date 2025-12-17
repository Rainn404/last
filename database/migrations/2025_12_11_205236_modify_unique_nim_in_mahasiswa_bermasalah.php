<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mahasiswa_bermasalah', function (Blueprint $table) {

            // Cek apakah kolom nim ada
            if (!Schema::hasColumn('mahasiswa_bermasalah', 'nim')) {
                return;
            }

            // Cek apakah unique index-nya ada
            $indexExists = collect(DB::select("
                SHOW INDEX FROM mahasiswa_bermasalah
                WHERE Key_name = 'mahasiswa_bermasalah_nim_unique'
            "))->isNotEmpty();

            // Jika ada → drop
            if ($indexExists) {
                $table->dropUnique(['nim']);   // paling aman
            }
        });
    }

    public function down()
    {
        Schema::table('mahasiswa_bermasalah', function (Blueprint $table) {

            if (Schema::hasColumn('mahasiswa_bermasalah', 'nim')) {
                $table->unique('nim');
            }

        });
    }
};
