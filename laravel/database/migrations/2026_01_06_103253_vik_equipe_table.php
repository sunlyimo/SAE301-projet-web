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
        Schema::create('vik_equipe', function (Blueprint $table) {
            $table->unsignedInteger('RAI_ID');
            $table->integer('COU_ID');
            $table->integer('EQU_ID');
            $table->unsignedInteger('UTI_ID');
            $table->string('EQU_NOM', 50);
            $table->string('EQU_IMAGE', 255)->nullable();
            $table->primary(['RAI_ID','COU_ID','EQU_ID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_equipe');
    }
};
