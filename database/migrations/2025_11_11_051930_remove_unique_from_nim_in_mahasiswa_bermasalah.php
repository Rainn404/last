<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if the unique constraint exists before dropping
        $indexExists = DB::select("
            SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
            WHERE TABLE_NAME = 'mahasiswa_bermasalah' 
            AND INDEX_NAME = 'mahasiswa_bermasalah_nim_unique'
        ");

        if (!empty($indexExists)) {
            Schema::table('mahasiswa_bermasalah', function (Blueprint $table) {
                $table->dropUnique(['nim']);
            });
        }
    }

    public function down()
    {
        Schema::table('mahasiswa_bermasalah', function (Blueprint $table) {
            $table->unique('nim');
        });
    }
};