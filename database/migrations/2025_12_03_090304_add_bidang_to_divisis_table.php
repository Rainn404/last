<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('divisis', function (Blueprint $table) {
            $table->json('bidang')->nullable()->after('deskripsi');
        });
    }

    public function down()
    {
        Schema::table('divisis', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });
    }
};
