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
        Schema::create('VIK_UTILISATEUR', function (Blueprint $table) {
            $table->id('UTI_ID');
            $table->string('UTI_EMAIL');
            $table->string('UTI_NOM');
            $table->string('UTI_PRENOM');
            $table->date('UTI_DATE_NAISSANCE');
            $table->string('UTI_ADRESSE')->nullable(); 
            $table->string('UTI_TELEPHONE', 20)->nullable();
            $table->string('password'); 
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VIK_UTILISATEUR');
    }
};