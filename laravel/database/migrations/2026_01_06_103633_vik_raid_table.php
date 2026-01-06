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
        Schema::create('vik_raid', function (Blueprint $table) {
            $table->increments('RAI_ID')->primary();
            $table->unsignedInteger('CLU_ID');
            $table->unsignedInteger('UTI_ID');
            $table->char('RAI_NOM', 50)->nullable();
            $table->dateTime('RAI_RAID_DATE_DEBUT')->nullable();
            $table->dateTime('RAI_RAID_DATE_FIN')->nullable();
            $table->dateTime('RAI_INSCRI_DATE_DEBUT')->nullable();
            $table->dateTime('RAI_INSCRI_DATE_FIN')->nullable();
            $table->char('RAI_CONTACT', 50)->nullable();
            $table->char('RAI_WEB', 50)->nullable();
            $table->char('RAI_LIEU', 50)->nullable();
            $table->char('RAI_IMAGE', 50)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_raid');
    }
};
