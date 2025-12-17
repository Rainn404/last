<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInMahasiswaTable extends Migration
{
    public function up()
    {
        // FIX: pakai tabel 'mahasiswas'
        if (Schema::hasTable('mahasiswas')) {
            DB::statement("
                ALTER TABLE mahasiswas 
                MODIFY status ENUM('Aktif', 'Non-Aktif', 'Cuti') 
                NOT NULL DEFAULT 'Aktif'
            ");
        }
    }

    public function down()
    {
        // rollback aman (biarkan sama)
        if (Schema::hasTable('mahasiswas')) {
            DB::statement("
                ALTER TABLE mahasiswas 
                MODIFY status ENUM('Aktif', 'Non-Aktif', 'Cuti') 
                NOT NULL DEFAULT 'Aktif'
            ");
        }
    }
}
