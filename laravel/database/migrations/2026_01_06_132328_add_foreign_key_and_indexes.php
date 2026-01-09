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
        Schema::table('vik_coureur', function (Blueprint $table) {
            $table->index('CLU_ID', 'i_fk_vik_coureur_vik_club');
            $table->foreign('CLU_ID', 'fk_vik_coureur_vik_club')
                  ->references('CLU_ID')->on('vik_club');

            $table->foreign('UTI_ID', 'fk_vik_coureur_vik_utilisateur')
                  ->references('UTI_ID')->on('vik_utilisateur');
        });

        // -------------------------
        // VIK_COURSE
        // -------------------------
        Schema::table('vik_course', function (Blueprint $table) {
            $table->index('TYP_ID', 'i_fk_vik_course_vik_course_type');
            $table->index('UTI_ID', 'i_fk_vik_course_vik_responsable_course');
            $table->index('RAI_ID', 'i_fk_vik_course_vik_raid');
            $table->index('DIF_NIVEAU', 'i_fk_vik_course_vik_tranche_difficulte');

            $table->foreign('TYP_ID', 'fk_vik_course_vik_course_type')
                  ->references('TYP_ID')->on('vik_course_type');

            $table->foreign('UTI_ID', 'fk_vik_course_vik_responsable_course')
                  ->references('UTI_ID')->on('vik_responsable_course');

            $table->foreign('RAI_ID', 'fk_vik_course_vik_raid')
                  ->references('RAI_ID')->on('vik_raid');

            $table->foreign('DIF_NIVEAU', 'fk_vik_course_vik_tranche_difficulte')
                  ->references('DIF_NIVEAU')->on('vik_tranche_difficulte');
        });

        // -------------------------
        // VIK_ADMINISTRATEUR
        // -------------------------
        Schema::table('vik_administrateur', function (Blueprint $table) {
            $table->foreign('UTI_ID', 'fk_vik_administrateur_vik_utilisateur')
                  ->references('UTI_ID')->on('vik_utilisateur');
        });

        // -------------------------
        // VIK_RESPONSABLE_RAID
        // -------------------------
        Schema::table('vik_responsable_raid', function (Blueprint $table) {
            $table->foreign('UTI_ID', 'fk_vik_responsable_raid_vik_utilisateur')
                  ->references('UTI_ID')->on('vik_utilisateur');
        });

        // -------------------------
        // VIK_EQUIPE
        // -------------------------
        Schema::table('vik_equipe', function (Blueprint $table) {
            $table->index(['RAI_ID','COU_ID'], 'i_fk_vik_equipe_vik_course');
            $table->index('UTI_ID', 'i_fk_vik_equipe_vik_utilisateur');

            $table->foreign(['RAI_ID','COU_ID'], 'fk_vik_equipe_vik_course')
                  ->references(['RAI_ID','COU_ID'])->on('vik_course');
            $table->foreign('UTI_ID', 'fk_vik_equipe_vik_utilisateur')
                  ->references('UTI_ID')->on('vik_utilisateur');
        });

        // -------------------------
        // VIK_RESULTAT
        // -------------------------
        Schema::table('vik_resultat', function (Blueprint $table) {
            $table->foreign(['RAI_ID','COU_ID','EQU_ID'], 'fk_vik_resultat_vik_equipe')
                  ->references(['RAI_ID','COU_ID','EQU_ID'])->on('vik_equipe');
        });

        // -------------------------
        // VIK_RAID
        // -------------------------
        Schema::table('vik_raid', function (Blueprint $table) {
            $table->index('UTI_ID', 'i_fk_vik_raid_vik_responsable_raid');
            $table->index('CLU_ID', 'i_fk_vik_raid_vik_club');

            $table->foreign('UTI_ID', 'fk_vik_raid_vik_utilisateur_raid')
                  ->references('UTI_ID')->on('vik_responsable_raid');
            $table->foreign('CLU_ID', 'fk_vik_raid_vik_club')
                  ->references('CLU_ID')->on('vik_club');
        });

        // -------------------------
        // VIK_RESPONSABLE_COURSE
        // -------------------------
        Schema::table('vik_responsable_course', function (Blueprint $table) {
            $table->foreign('UTI_ID', 'fk_vik_responsable_course_vik_utilisateur')
                  ->references('UTI_ID')->on('vik_utilisateur');
        });

        // -------------------------
        // VIK_RESPONSABLE_CLUB
        // -------------------------
        Schema::table('vik_responsable_club', function (Blueprint $table) {
            $table->index('CLU_ID', 'i_fk_vik_responsable_club_vik_club');

            $table->foreign('UTI_ID', 'fk_vik_responsable_club_vik_utilisateur')
                  ->references('UTI_ID')->on('vik_utilisateur');
            $table->foreign('CLU_ID', 'fk_vik_responsable_club_vik_club')
                  ->references('CLU_ID')->on('vik_club');
        });

        // -------------------------
        // VIK_APPARTIENT
        // -------------------------
        Schema::table('vik_appartient', function (Blueprint $table) {
            $table->index('UTI_ID', 'i_fk_vik_appartient_vik_coureur');
            $table->index(['RAI_ID','COU_ID','EQU_ID'], 'i_fk_vik_appartient_vik_equipe');

            $table->foreign('UTI_ID', 'fk_vik_appartient_vik_coureur')
                  ->references('UTI_ID')->on('vik_coureur');
            $table->foreign(['RAI_ID','COU_ID','EQU_ID'], 'fk_vik_appartient_vik_equipe')
                  ->references(['RAI_ID','COU_ID','EQU_ID'])->on('vik_equipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vik_appartient', function (Blueprint $table) {
            $table->dropForeign(['UTI_ID']);
            $table->dropForeign(['RAI_ID','COU_ID','EQU_ID']);
            $table->dropIndex('i_fk_vik_appartient_vik_coureur');
            $table->dropIndex('i_fk_vik_appartient_vik_equipe');
        });

        Schema::table('vik_responsable_club', function (Blueprint $table) {
            $table->dropForeign(['UTI_ID']);
            $table->dropForeign(['CLU_ID']);
            $table->dropIndex('i_fk_vik_responsable_club_vik_club');
        });

        Schema::table('vik_responsable_course', function (Blueprint $table) {
            $table->dropForeign(['UTI_ID']);
        });

        Schema::table('vik_raid', function (Blueprint $table) {
            $table->dropForeign(['UTI_ID']);
            $table->dropForeign(['CLU_ID']);
            $table->dropIndex('i_fk_vik_raid_vik_responsable_raid');
            $table->dropIndex('i_fk_vik_raid_vik_club');
        });

        Schema::table('vik_resultat', function (Blueprint $table) {
            $table->dropForeign(['RAI_ID','COU_ID','EQU_ID']);
        });

        Schema::table('vik_equipe', function (Blueprint $table) {
            $table->dropForeign(['RAI_ID','COU_ID']);
            $table->dropForeign(['UTI_ID']);
            $table->dropIndex('i_fk_vik_equipe_vik_course');
            $table->dropIndex('i_fk_vik_equipe_vik_utilisateur');
        });

        Schema::table('vik_responsable_raid', function (Blueprint $table) {
            $table->dropForeign(['UTI_ID']);
        });

        Schema::table('vik_administrateur', function (Blueprint $table) {
            $table->dropForeign(['UTI_ID']);
        });

        Schema::table('vik_course', function (Blueprint $table) {
            $table->dropForeign(['TYP_ID']);
            $table->dropForeign(['UTI_ID']);
            $table->dropForeign(['RAI_ID']);
            $table->dropForeign(['DIF_NIVEAU']);
            $table->dropIndex('i_fk_vik_course_vik_course_type');
            $table->dropIndex('i_fk_vik_course_vik_responsable_course');
            $table->dropIndex('i_fk_vik_course_vik_raid');
            $table->dropIndex('i_fk_vik_course_vik_tranche_difficulte');
        });

        Schema::table('vik_coureur', function (Blueprint $table) {
            $table->dropForeign(['CLU_ID']);
            $table->dropForeign(['UTI_ID']);
            $table->dropIndex('i_fk_vik_coureur_vik_club');
        });
    }
};
