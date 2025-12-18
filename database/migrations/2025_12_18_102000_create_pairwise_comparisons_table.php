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
        Schema::create('pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('criterion1_id');
            $table->unsignedBigInteger('criterion2_id');
            $table->decimal('value', 10, 6);
            $table->timestamps();

            // Foreign keys
            $table->foreign('criterion1_id')->references('id_criterion')->on('criteria')->onDelete('cascade');
            $table->foreign('criterion2_id')->references('id_criterion')->on('criteria')->onDelete('cascade');

            // Unique constraint to ensure one comparison per pair
            $table->unique(['criterion1_id', 'criterion2_id']);

            // Indexes for queries
            $table->index('criterion1_id');
            $table->index('criterion2_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairwise_comparisons');
    }
};
