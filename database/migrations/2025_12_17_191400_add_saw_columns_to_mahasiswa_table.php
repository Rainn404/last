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
        // Tambah kolom untuk data ranking SAW
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswa', 'ipk')) {
                $table->decimal('ipk', 3, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('mahasiswa', 'juara')) {
                $table->integer('juara')->nullable()->after('ipk')->comment('1=Anggota, 3=Juara3, 5=Juara2, 7=Juara1');
            }
            if (!Schema::hasColumn('mahasiswa', 'tingkatan')) {
                $table->integer('tingkatan')->nullable()->after('juara')->comment('1=Internal, 3=Kabupaten, 5=Provinsi, 7=Nasional, 9=Internasional');
            }
            if (!Schema::hasColumn('mahasiswa', 'keterangan')) {
                $table->integer('keterangan')->nullable()->after('tingkatan')->comment('1=Non-Akademik, 3=Akademik');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['ipk', 'juara', 'tingkatan', 'keterangan']);
        });
    }
};
