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
        Schema::create('vik_resultat', function (Blueprint $table) {
            $table->unsignedInteger('RAI_ID');
            $table->integer('COU_ID');
            $table->integer('EQU_ID');
            $table->char('RES_RANG', 32)->nullable();
            $table->char('RES_TEMPS', 32)->nullable();
            $table->integer('RES_POINT')->nullable();

            $table->primary(['RAI_ID','COU_ID','EQU_ID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_resultat');
    }
};
