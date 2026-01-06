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
        Schema::create('vik_appartient', function (Blueprint $table) {
            $table->unsignedInteger('UTI_ID');
            $table->unsignedInteger('RAI_ID');
            $table->integer('COU_ID');
            $table->integer('EQU_ID');

            $table->primary(['UTI_ID','RAI_ID','COU_ID','EQU_ID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_appartient');
    }
};
