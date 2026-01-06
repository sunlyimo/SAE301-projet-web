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
        Schema::create('vik_club', function (Blueprint $table) {
            $table->increments('CLU_ID')->primary();
            $table->char('CLU_NOM', 50)->nullable();
            $table->char('CLU_RUE', 100)->nullable();
            $table->char('CLU_CODE_POSTAL', 10)->nullable();
            $table->char('CLU_VILLE', 50)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_club');
    }
};
