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
        Schema::create('vik_responsable_raid', function (Blueprint $table) {
            $table->unsignedInteger('UTI_ID')->primary();
            $table->char('UTI_EMAIL', 32)->nullable();
            $table->char('UTI_NOM', 50)->nullable();
            $table->char('UTI_PRENOM', 50)->nullable();
            $table->date('UTI_DATE_NAISSANCE')->nullable();
            $table->char('UTI_RUE', 100)->nullable();
            $table->char('UTI_CODE_POSTAL', 6)->nullable();
            $table->char('UTI_VILLE', 50)->nullable();
            $table->char('UTI_TELEPHONE', 16)->nullable();
            $table->char('UTI_LICENCE', 15)->nullable();
            $table->char('UTI_NOM_UTILISATEUR', 255);
            $table->char('UTI_MOT_DE_PASSE', 32)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_responsable_raid');
    }
};
