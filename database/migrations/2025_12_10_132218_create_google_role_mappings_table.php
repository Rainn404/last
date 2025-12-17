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
        Schema::create('google_role_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('email_pattern'); // Pattern email (contoh: *@admin.com, admin@hima.com)
            $table->enum('role', ['super_admin', 'admin', 'mahasiswa']); // Role yang akan diberikan
            $table->integer('priority')->default(0); // Prioritas (yang lebih tinggi akan diutamakan)
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->text('description')->nullable(); // Deskripsi mapping
            $table->timestamps();

            // Index untuk performa
            $table->index(['email_pattern', 'is_active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_role_mappings');
    }
};
