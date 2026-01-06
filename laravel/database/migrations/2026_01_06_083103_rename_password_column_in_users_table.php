<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('VIK_UTILISATEUR', function (Blueprint $table) {
            $table->renameColumn('password', 'UTI_MOT_DE_PASSE');
        });
    }

    public function down(): void
    {
        Schema::table('VIK_UTILISATEUR', function (Blueprint $table) {
            $table->renameColumn('UTI_MOT_DE_PASSE', 'password');
        });
    }
};
