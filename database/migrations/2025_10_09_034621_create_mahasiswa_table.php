<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaTable extends Migration
{
    public function up()
    {
        // FIX: pakai tabel 'mahasiswas' (standar Laravel)
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->enum('status', ['Aktif', 'Non-Aktif', 'Cuti'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        // FIX: konsisten dengan nama tabel di atas
        Schema::dropIfExists('mahasiswas');
    }
}
