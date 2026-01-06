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
        Schema::create('vik_coureur', function (Blueprint $table) {
            $table->unsignedInteger('UTI_ID')->primary();
            $table->unsignedInteger('CLU_ID')->nullable();
            $table->char('CRR_PPS', 32)->nullable();
            $table->char('UTI_EMAIL', 100)->nullable();
            $table->char('UTI_NOM', 50)->nullable();
            $table->char('UTI_PRENOM', 50)->nullable();
            $table->date('UTI_DATE_NAISSANCE')->nullable();
            $table->char('UTI_RUE', 100)->nullable();
            $table->char('UTI_CODE_POSTAL', 10)->nullable();
            $table->char('UTI_VILLE', 50)->nullable();
            $table->char('UTI_TELEPHONE', 16)->nullable();
            $table->char('UTI_LICENCE', 15)->nullable();
            $table->char('UTI_NOM_UTILISATEUR', 50);
            $table->char('UTI_MOT_DE_PASSE', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_coureur');
    }
};
