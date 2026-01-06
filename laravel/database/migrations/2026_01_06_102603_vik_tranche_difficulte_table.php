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
        Schema::create('vik_tranche_difficulte', function (Blueprint $table) {
            $table->integer('DIF_NIVEAU')->primary();
            $table->char('DIF_DESCRIPTION', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_tranche_difficulte');
    }
};
