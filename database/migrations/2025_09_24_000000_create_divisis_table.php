<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->id('id_divisi');
            $table->string('nama_divisi', 100)->unique();
            $table->string('ketua_divisi', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('divisis');
    }
};