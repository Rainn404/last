<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode kriteria: C1, C2, PK, dll');
            $table->string('name')->comment('Nama kriteria');
            $table->text('description')->nullable()->comment('Deskripsi kriteria');
            $table->enum('type', ['benefit', 'cost'])->default('benefit')->comment('Benefit: semakin besar semakin baik, Cost: semakin kecil semakin baik');
            $table->decimal('weight', 5, 3)->nullable()->comment('Bobot kriteria hasil AHP');
            $table->integer('order')->default(0)->comment('Urutan tampil');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteria');
    }
};