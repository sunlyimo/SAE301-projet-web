<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $pass = Hash::make('Root123!');
        Log::debug($pass);
        Log::debug("count:".strlen($pass));
        $user = new User([
            'UTI_NOM' => 'Admin',
            'UTI_PRENOM' => 'Vikazim',
            'UTI_EMAIL' => 'admin.vikazim@mail.fr',
            'UTI_DATE_NAISSANCE' => '1980-01-01',
            'UTI_MOT_DE_PASSE' => $pass,
            'UTI_NOM_UTILISATEUR' => 'admin_sys',
            'UTI_RUE' => 'Rue des lilas',
            'UTI_CODE_POSTAL' => '76000',
            'UTI_VILLE' => 'Rouen',
            'UTI_TELEPHONE' => '0600000000',
            'UTI_LICENCE' => "10000",
        ]);
        $user->save();
        $admin = new Administrateur([
            'UTI_ID' => $user->UTI_ID,
            'UTI_NOM' => 'Admin',
            'UTI_PRENOM' => 'Vikazim',
            'UTI_EMAIL' => 'admin.vikazim@mail.fr',
            'UTI_DATE_NAISSANCE' => '1980-01-01',
            'UTI_MOT_DE_PASSE' => $pass,
            'UTI_NOM_UTILISATEUR' => 'admin_sys',
            'UTI_RUE' => 'Rue des lilas',
            'UTI_CODE_POSTAL' => '76000',
            'UTI_VILLE' => 'Rouen',
            'UTI_TELEPHONE' => '0600000000',
            'UTI_LICENCE' => "10000",
        ]);
        $admin->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
