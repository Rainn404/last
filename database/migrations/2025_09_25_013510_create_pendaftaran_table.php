<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->string('nim', 30);
            $table->string('nama', 150);
            $table->tinyInteger('semester')->nullable();
            $table->text('alasan_mendaftar')->nullable();
            
            // Divisi yang dipilih saat mendaftar (wajib)
            $table->foreignId('id_divisi')->constrained('divisis', 'id_divisi')->onDelete('restrict');
            $table->text('alasan_divisi');
            
            // TAMBAHKAN KOLOM INI ↓
            $table->text('pengalaman')->nullable();
            $table->text('skill')->nullable();
            
            $table->string('dokumen', 255)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->enum('status_pendaftaran', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran');
    }
};