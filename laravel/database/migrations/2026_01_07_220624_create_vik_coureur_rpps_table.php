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
        Schema::create('vik_coureur_rpps', function (Blueprint $table) {
            $table->id('CRP_ID');
            $table->unsignedInteger('UTI_ID');
            $table->unsignedInteger('RAI_ID');
            $table->integer('COU_ID');
            $table->char('CRP_NUMERO_RPPS', 11);

            // Clé étrangère vers coureur
            $table->foreign('UTI_ID')->references('UTI_ID')->on('vik_coureur')->onDelete('cascade');

            // Clé étrangère vers course (clé composite)
            $table->foreign(['RAI_ID', 'COU_ID'])->references(['RAI_ID', 'COU_ID'])->on('vik_course')->onDelete('cascade');

            // Index unique : un coureur ne peut avoir qu'un seul RPPS par course
            $table->unique(['UTI_ID', 'RAI_ID', 'COU_ID']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_coureur_rpps');
    }
};
