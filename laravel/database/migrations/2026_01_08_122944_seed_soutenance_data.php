<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\SoutenanceSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Exécute le seeder de données de soutenance
        $seeder = new SoutenanceSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionnel : supprimer les données insérées lors du rollback
        // Attention : cela supprimera TOUTES les données de ces tables
        /*
        DB::table('VIK_APPARTIENT')->whereIn('RAI_ID', [100, 101])->delete();
        DB::table('VIK_EQUIPE')->whereIn('RAI_ID', [100, 101])->delete();
        DB::table('VIK_COURSE')->whereIn('RAI_ID', [100, 101])->delete();
        DB::table('VIK_RAID')->whereIn('RAI_ID', [100, 101])->delete();
        DB::table('VIK_RESPONSABLE_COURSE')->whereIn('UTI_ID', [51, 66, 56, 62, 70])->delete();
        DB::table('VIK_RESPONSABLE_RAID')->whereIn('UTI_ID', [66, 56])->delete();
        DB::table('VIK_COUREUR')->whereBetween('UTI_ID', [51, 70])->delete();
        DB::table('VIK_UTILISATEUR')->whereBetween('UTI_ID', [51, 70])->delete();
        DB::table('VIK_CLUB')->whereIn('CLU_ID', [4, 5, 6, 7])->delete();
        DB::table('vik_tranche_difficulte')->whereBetween('DIF_NIVEAU', [1, 5])->delete();
        */
    }
};
