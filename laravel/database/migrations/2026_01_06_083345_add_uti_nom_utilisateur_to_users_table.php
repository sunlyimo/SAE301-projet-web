<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('VIK_UTILISATEUR', function (Blueprint $table) {
            $table->string('UTI_NOM_UTILISATEUR', 50)->unique()->after('UTI_EMAIL');
        });
    }

    public function down(): void
    {
        Schema::table('VIK_UTILISATEUR', function (Blueprint $table) {
            $table->dropColumn('UTI_NOM_UTILISATEUR');
        });
    }
};
