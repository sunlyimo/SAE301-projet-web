<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('VIK_COURSE', function (Blueprint $table) {
            $table->integer('RAI_ID');
            $table->integer('COU_ID');
            $table->primary(['RAI_ID', 'COU_ID']);

            $table->integer('TYP_ID');
            $table->integer('DIF_NIVEAU');
            $table->integer('UTI_ID');

            $table->string('COU_NOM', 50)->nullable();
            $table->dateTime('COU_DATE_DEBUT')->nullable();
            $table->dateTime('COU_DATE_FIN')->nullable();
            $table->decimal('COU_PRIX', 13, 2)->nullable();
            $table->decimal('COU_PRIX_ENFANT', 13, 2)->nullable();
            $table->integer('COU_PARTICIPANT_MIN')->nullable();
            $table->integer('COU_PARTICIPANT_MAX')->nullable();
            $table->integer('COU_EQUIPE_MIN')->nullable();
            $table->integer('COU_EQUIPE_MAX')->nullable();
            $table->integer('COU_PARTICIPANT_PAR_EQUIPE_MAX')->nullable();
            $table->decimal('COU_REPAS_PRIX', 13, 2)->nullable();
            $table->decimal('COU_REDUCTION', 13, 2)->nullable();
            $table->string('COU_LIEU', 100)->nullable();
            $table->integer('COU_AGE_MIN')->nullable();
            $table->integer('COU_AGE_SEUL')->nullable();
            $table->integer('COU_AGE_ACCOMPAGNATEUR')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_course');
    }
};
