<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insert sample criteria data
        DB::table('criteria')->insert([
            [
                'code' => 'K1',
                'name' => 'Nilai Akademik',
                'description' => 'Prestasi akademik calon anggota',
                'type' => 'benefit',
                'weight' => 25.00,
                'priority' => 1,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'K2',
                'name' => 'Pengalaman Organisasi',
                'description' => 'Pengalaman dalam organisasi atau kepemimpinan',
                'type' => 'benefit',
                'weight' => 20.00,
                'priority' => 2,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'K3',
                'name' => 'Kemampuan Komunikasi',
                'description' => 'Kemampuan berkomunikasi dan presentasi',
                'type' => 'benefit',
                'weight' => 20.00,
                'priority' => 3,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'K4',
                'name' => 'Kehadiran Saat Testing',
                'description' => 'Ketepatan waktu dan konsistensi kehadiran',
                'type' => 'benefit',
                'weight' => 15.00,
                'priority' => 4,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'K5',
                'name' => 'Kesesuaian Divisi',
                'description' => 'Kesesuaian dengan minat dan visi divisi',
                'type' => 'benefit',
                'weight' => 20.00,
                'priority' => 5,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('criteria')->delete();
    }
};
