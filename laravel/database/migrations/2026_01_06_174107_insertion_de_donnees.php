<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
        // Ensure admin user exists (create or update) with UTI_ID = 100
        DB::table('VIK_UTILISATEUR')->updateOrInsert(
            ['UTI_ID' => 100],
            [
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
                'UTI_ID' => 100,
            ]
        );

        DB::table('VIK_ADMINISTRATEUR')->updateOrInsert(
            ['UTI_ID' => 100],
            [
                'UTI_EMAIL' => 'admin.vikazim@mail.fr',
                'UTI_NOM' => 'Admin',
                'UTI_PRENOM' => 'Vikazim',
                'UTI_DATE_NAISSANCE' => '1980-01-01',
                'UTI_MOT_DE_PASSE' => $pass,
                'UTI_NOM_UTILISATEUR' => 'admin_sys',
                'UTI_RUE' => 'Rue des lilas',
                'UTI_CODE_POSTAL' => '76000',
                'UTI_VILLE' => 'Rouen',
                'UTI_TELEPHONE' => '0600000000',
                'UTI_LICENCE' => "10000",
                'UTI_ID' => 100,
            ]
        );

        // Insère les autres données via SQL brut. NOTE: la ligne d'admin dans VIK_UTILISATEUR a été retirée
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Tables de référence (insertOrIgnore pour idempotence)
        DB::table('VIK_CLUB')->insertOrIgnore([
            ['CLU_ID'=>1,'CLU_NOM'=>'Club Rouen','CLU_RUE'=>'1 Rue de la Paix','CLU_CODE_POSTAL'=>'76000','CLU_VILLE'=>'Rouen','created_at'=>now(),'updated_at'=>now()],
            ['CLU_ID'=>2,'CLU_NOM'=>'Club Caen','CLU_RUE'=>'10 Avenue du Stade','CLU_CODE_POSTAL'=>'14000','CLU_VILLE'=>'Caen','created_at'=>now(),'updated_at'=>now()],
            ['CLU_ID'=>3,'CLU_NOM'=>'Club Lyon','CLU_RUE'=>'5 Boulevard des Sports','CLU_CODE_POSTAL'=>'69000','CLU_VILLE'=>'Lyon','created_at'=>now(),'updated_at'=>now()],
        ]);

        DB::table('VIK_COURSE_TYPE')->insertOrIgnore([
            ['TYP_ID'=>1,'TYP_DESCRIPTION'=>'Course de vitesse'],
            ['TYP_ID'=>2,'TYP_DESCRIPTION'=>'Course d’endurance'],
            ['TYP_ID'=>3,'TYP_DESCRIPTION'=>'Course en relais'],
            ['TYP_ID'=>4,'TYP_DESCRIPTION'=>'Course en équipe'],
            ['TYP_ID'=>5,'TYP_DESCRIPTION'=>'Course enfants'],
        ]);

        DB::table('VIK_TRANCHE_DIFFICULTE')->insertOrIgnore([
            ['DIF_NIVEAU'=>1,'DIF_DESCRIPTION'=>'Débutant'],
            ['DIF_NIVEAU'=>2,'DIF_DESCRIPTION'=>'Intermédiaire'],
            ['DIF_NIVEAU'=>3,'DIF_DESCRIPTION'=>'Avancé'],
            ['DIF_NIVEAU'=>4,'DIF_DESCRIPTION'=>'Expert'],
            ['DIF_NIVEAU'=>5,'DIF_DESCRIPTION'=>'Elite'],
        ]);

        // Insère les utilisateurs (1..50) avec mot de passe haché (insertOrIgnore pour idempotence)
        $plain = 'pass123';
        $users = [
            ['UTI_ID'=>1,'UTI_EMAIL'=>'Robin.Paul@mail.fr','UTI_NOM'=>'Paul','UTI_PRENOM'=>'Robin','UTI_DATE_NAISSANCE'=>'2005-08-08','UTI_RUE'=>'Rue des Lilas','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000018','UTI_LICENCE'=>'18018','UTI_NOM_UTILISATEUR'=>'rpaul','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>2,'UTI_EMAIL'=>'Lemoine.Alice@mail.fr','UTI_NOM'=>'Alice','UTI_PRENOM'=>'Lemoine','UTI_DATE_NAISSANCE'=>'2000-11-11','UTI_RUE'=>'Avenue des Fleurs','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000021','UTI_LICENCE'=>'21021','UTI_NOM_UTILISATEUR'=>'alemoine','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>3,'UTI_EMAIL'=>'Fabre.Leo@mail.fr','UTI_NOM'=>'Leo','UTI_PRENOM'=>'Fabre','UTI_DATE_NAISSANCE'=>'1998-12-12','UTI_RUE'=>'Boulevard du Parc','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000022','UTI_LICENCE'=>'22022','UTI_NOM_UTILISATEUR'=>'lfabre','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>4,'UTI_EMAIL'=>'Carpentier.Eva@mail.fr','UTI_NOM'=>'Eva','UTI_PRENOM'=>'Carpentier','UTI_DATE_NAISSANCE'=>'2001-01-05','UTI_RUE'=>'Rue de l’Église','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000023','UTI_LICENCE'=>'23023','UTI_NOM_UTILISATEUR'=>'ecarpentier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>5,'UTI_EMAIL'=>'Dupont.Noah@mail.fr','UTI_NOM'=>'Noah','UTI_PRENOM'=>'Dupont','UTI_DATE_NAISSANCE'=>'2002-02-14','UTI_RUE'=>'Impasse du Moulin','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000024','UTI_LICENCE'=>'24024','UTI_NOM_UTILISATEUR'=>'ndupont','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>6,'UTI_EMAIL'=>'Renaud.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Renaud','UTI_DATE_NAISSANCE'=>'2003-03-23','UTI_RUE'=>'Rue des Érables','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000025','UTI_LICENCE'=>'25025','UTI_NOM_UTILISATEUR'=>'lrenaud','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>7,'UTI_EMAIL'=>'Gautier.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Gautier','UTI_DATE_NAISSANCE'=>'1999-04-30','UTI_RUE'=>'Chemin des Vignes','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000026','UTI_LICENCE'=>'26026','UTI_NOM_UTILISATEUR'=>'hgautier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>8,'UTI_EMAIL'=>'Morel.Zoé@mail.fr','UTI_NOM'=>'Zoé','UTI_PRENOM'=>'Morel','UTI_DATE_NAISSANCE'=>'2004-05-15','UTI_RUE'=>'Rue du Château','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000027','UTI_LICENCE'=>'27027','UTI_NOM_UTILISATEUR'=>'zmorel','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>9,'UTI_EMAIL'=>'Perrin.Théo@mail.fr','UTI_NOM'=>'Théo','UTI_PRENOM'=>'Perrin','UTI_DATE_NAISSANCE'=>'2005-06-21','UTI_RUE'=>'Rue des Cerisiers','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000028','UTI_LICENCE'=>'28028','UTI_NOM_UTILISATEUR'=>'tperrin','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>10,'UTI_EMAIL'=>'Marchand.Aurélie@mail.fr','UTI_NOM'=>'Aurélie','UTI_PRENOM'=>'Marchand','UTI_DATE_NAISSANCE'=>'1997-07-07','UTI_RUE'=>'Avenue Victor Hugo','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000029','UTI_LICENCE'=>'29029','UTI_NOM_UTILISATEUR'=>'amarchand','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>11,'UTI_EMAIL'=>'Blanchard.Max@mail.fr','UTI_NOM'=>'Max','UTI_PRENOM'=>'Blanchard','UTI_DATE_NAISSANCE'=>'2000-08-08','UTI_RUE'=>'Rue du Pont','UTI_CODE_POSTAL'=>'69000','UTI_VILLE'=>'Lyon','UTI_TELEPHONE'=>'0600000030','UTI_LICENCE'=>'30030','UTI_NOM_UTILISATEUR'=>'mblanchard','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>12,'UTI_EMAIL'=>'Dufour.Clara@mail.fr','UTI_NOM'=>'Clara','UTI_PRENOM'=>'Dufour','UTI_DATE_NAISSANCE'=>'2001-09-09','UTI_RUE'=>'Rue des Acacias','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000031','UTI_LICENCE'=>'31031','UTI_NOM_UTILISATEUR'=>'cdufour','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>13,'UTI_EMAIL'=>'Rousseau.Jules@mail.fr','UTI_NOM'=>'Jules','UTI_PRENOM'=>'Rousseau','UTI_DATE_NAISSANCE'=>'1998-10-10','UTI_RUE'=>'Place de la Liberté','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000032','UTI_LICENCE'=>'32032','UTI_NOM_UTILISATEUR'=>'jrousseau','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>14,'UTI_EMAIL'=>'Leclerc.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Leclerc','UTI_DATE_NAISSANCE'=>'2002-11-11','UTI_RUE'=>'Rue des Tilleuls','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000033','UTI_LICENCE'=>'33033','UTI_NOM_UTILISATEUR'=>'lleclerc','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>15,'UTI_EMAIL'=>'Fayard.Lucas@mail.fr','UTI_NOM'=>'Lucas','UTI_PRENOM'=>'Fayard','UTI_DATE_NAISSANCE'=>'1999-12-12','UTI_RUE'=>'Boulevard Saint-Michel','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000034','UTI_LICENCE'=>'34034','UTI_NOM_UTILISATEUR'=>'lfayard','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>16,'UTI_EMAIL'=>'Noel.Eva@mail.fr','UTI_NOM'=>'Eva','UTI_PRENOM'=>'Noel','UTI_DATE_NAISSANCE'=>'2000-01-01','UTI_RUE'=>'Rue du Soleil','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000035','UTI_LICENCE'=>'35035','UTI_NOM_UTILISATEUR'=>'enoel','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>17,'UTI_EMAIL'=>'Barbier.Tom@mail.fr','UTI_NOM'=>'Tom','UTI_PRENOM'=>'Barbier','UTI_DATE_NAISSANCE'=>'2001-02-02','UTI_RUE'=>'Rue des Peupliers','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000036','UTI_LICENCE'=>'36036','UTI_NOM_UTILISATEUR'=>'tbarbier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>18,'UTI_EMAIL'=>'Louis.Alice@mail.fr','UTI_NOM'=>'Alice','UTI_PRENOM'=>'Louis','UTI_DATE_NAISSANCE'=>'2003-03-03','UTI_RUE'=>'Rue des Marronniers','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000037','UTI_LICENCE'=>'37037','UTI_NOM_UTILISATEUR'=>'alouis','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>19,'UTI_EMAIL'=>'Guillaume.Noah@mail.fr','UTI_NOM'=>'Noah','UTI_PRENOM'=>'Guillaume','UTI_DATE_NAISSANCE'=>'2002-04-04','UTI_RUE'=>'Avenue de la Gare','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000038','UTI_LICENCE'=>'38038','UTI_NOM_UTILISATEUR'=>'nguillaume','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>20,'UTI_EMAIL'=>'Pons.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Pons','UTI_DATE_NAISSANCE'=>'2004-05-05','UTI_RUE'=>'Rue des Bouleaux','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000039','UTI_LICENCE'=>'39039','UTI_NOM_UTILISATEUR'=>'lpons','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>21,'UTI_EMAIL'=>'Martinez.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Martinez','UTI_DATE_NAISSANCE'=>'2000-06-06','UTI_RUE'=>'Rue de la Fontaine','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000040','UTI_LICENCE'=>'40040','UTI_NOM_UTILISATEUR'=>'hmartinez','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>22,'UTI_EMAIL'=>'Jacquet.Zoé@mail.fr','UTI_NOM'=>'Zoé','UTI_PRENOM'=>'Jacquet','UTI_DATE_NAISSANCE'=>'2001-07-07','UTI_RUE'=>'Rue des Ormes','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000041','UTI_LICENCE'=>'41041','UTI_NOM_UTILISATEUR'=>'zjacquet','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>23,'UTI_EMAIL'=>'Benoit.Théo@mail.fr','UTI_NOM'=>'Théo','UTI_PRENOM'=>'Benoit','UTI_DATE_NAISSANCE'=>'1999-08-08','UTI_RUE'=>'Chemin du Lac','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000042','UTI_LICENCE'=>'42042','UTI_NOM_UTILISATEUR'=>'tbenoit','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>24,'UTI_EMAIL'=>'Fournier.Aurélie@mail.fr','UTI_NOM'=>'Aurélie','UTI_PRENOM'=>'Fournier','UTI_DATE_NAISSANCE'=>'2003-09-09','UTI_RUE'=>'Rue du Stade','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000043','UTI_LICENCE'=>'43043','UTI_NOM_UTILISATEUR'=>'afournier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>25,'UTI_EMAIL'=>'Meyer.Max@mail.fr','UTI_NOM'=>'Max','UTI_PRENOM'=>'Meyer','UTI_DATE_NAISSANCE'=>'2002-10-10','UTI_RUE'=>'Rue de la Montagne','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000044','UTI_LICENCE'=>'44044','UTI_NOM_UTILISATEUR'=>'mmeyer','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>26,'UTI_EMAIL'=>'Henry.Clara@mail.fr','UTI_NOM'=>'Clara','UTI_PRENOM'=>'Henry','UTI_DATE_NAISSANCE'=>'2000-11-11','UTI_RUE'=>'Avenue de la République','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000045','UTI_LICENCE'=>'45045','UTI_NOM_UTILISATEUR'=>'chenry','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>27,'UTI_EMAIL'=>'Legrand.Jules@mail.fr','UTI_NOM'=>'Jules','UTI_PRENOM'=>'Legrand','UTI_DATE_NAISSANCE'=>'2001-12-12','UTI_RUE'=>'Rue des Roses','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000046','UTI_LICENCE'=>'46046','UTI_NOM_UTILISATEUR'=>'jlegrand','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>28,'UTI_EMAIL'=>'Lopez.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Lopez','UTI_DATE_NAISSANCE'=>'2004-01-01','UTI_RUE'=>'Rue de la Paix','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000047','UTI_LICENCE'=>'47047','UTI_NOM_UTILISATEUR'=>'llopez','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>29,'UTI_EMAIL'=>'Riviere.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Riviere','UTI_DATE_NAISSANCE'=>'2002-02-02','UTI_RUE'=>'Impasse des Fleurs','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000048','UTI_LICENCE'=>'48048','UTI_NOM_UTILISATEUR'=>'hriviere','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>30,'UTI_EMAIL'=>'Olivier.Zoé@mail.fr','UTI_NOM'=>'Zoé','UTI_PRENOM'=>'Olivier','UTI_DATE_NAISSANCE'=>'2003-03-03','UTI_RUE'=>'Rue du Verger','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000049','UTI_LICENCE'=>'49049','UTI_NOM_UTILISATEUR'=>'zolivier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>31,'UTI_EMAIL'=>'Fontaine.Théo@mail.fr','UTI_NOM'=>'Théo','UTI_PRENOM'=>'Fontaine','UTI_DATE_NAISSANCE'=>'2000-04-04','UTI_RUE'=>'Boulevard du Nord','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000050','UTI_LICENCE'=>'50050','UTI_NOM_UTILISATEUR'=>'tfontaine','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>32,'UTI_EMAIL'=>'Dupuis.Marie@mail.fr','UTI_NOM'=>'Marie','UTI_PRENOM'=>'Dupuis','UTI_DATE_NAISSANCE'=>'2003-05-05','UTI_RUE'=>'Rue des Lilas Bleus','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000051','UTI_LICENCE'=>'51051','UTI_NOM_UTILISATEUR'=>'mdupuis','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>33,'UTI_EMAIL'=>'Marchal.Léo@mail.fr','UTI_NOM'=>'Léo','UTI_PRENOM'=>'Marchal','UTI_DATE_NAISSANCE'=>'2001-06-06','UTI_RUE'=>'Rue de l’École','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000052','UTI_LICENCE'=>'52052','UTI_NOM_UTILISATEUR'=>'lmarchal','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>34,'UTI_EMAIL'=>'Fernandez.Alice@mail.fr','UTI_NOM'=>'Alice','UTI_PRENOM'=>'Fernandez','UTI_DATE_NAISSANCE'=>'2002-07-07','UTI_RUE'=>'Rue des Primevères','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000053','UTI_LICENCE'=>'53053','UTI_NOM_UTILISATEUR'=>'afernandez','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>35,'UTI_EMAIL'=>'Garnier.Noah@mail.fr','UTI_NOM'=>'Noah','UTI_PRENOM'=>'Garnier','UTI_DATE_NAISSANCE'=>'2004-08-08','UTI_RUE'=>'Chemin des Pins','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000054','UTI_LICENCE'=>'54054','UTI_NOM_UTILISATEUR'=>'ngarnier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>36,'UTI_EMAIL'=>'Chevalier.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Chevalier','UTI_DATE_NAISSANCE'=>'2003-09-09','UTI_RUE'=>'Rue de la Plage','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000055','UTI_LICENCE'=>'55055','UTI_NOM_UTILISATEUR'=>'lchevalier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>37,'UTI_EMAIL'=>'Colin.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Colin','UTI_DATE_NAISSANCE'=>'2000-10-10','UTI_RUE'=>'Rue des Violettes','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000056','UTI_LICENCE'=>'56056','UTI_NOM_UTILISATEUR'=>'hcolin','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>38,'UTI_EMAIL'=>'Blondel.Eva@mail.fr','UTI_NOM'=>'Eva','UTI_PRENOM'=>'Blondel','UTI_DATE_NAISSANCE'=>'2002-11-11','UTI_RUE'=>'Rue de la Gare','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000057','UTI_LICENCE'=>'57057','UTI_NOM_UTILISATEUR'=>'eblondel','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>39,'UTI_EMAIL'=>'Baron.Max@mail.fr','UTI_NOM'=>'Max','UTI_PRENOM'=>'Baron','UTI_DATE_NAISSANCE'=>'2001-12-12','UTI_RUE'=>'Avenue des Champs','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000058','UTI_LICENCE'=>'58058','UTI_NOM_UTILISATEUR'=>'mbaron','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>40,'UTI_EMAIL'=>'Philippe.Alice@mail.fr','UTI_NOM'=>'Alice','UTI_PRENOM'=>'Philippe','UTI_DATE_NAISSANCE'=>'2004-01-01','UTI_RUE'=>'Rue du Levant','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000059','UTI_LICENCE'=>'59059','UTI_NOM_UTILISATEUR'=>'aphilippe','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>41,'UTI_EMAIL'=>'Perrier.Noah@mail.fr','UTI_NOM'=>'Noah','UTI_PRENOM'=>'Perrier','UTI_DATE_NAISSANCE'=>'2000-02-02','UTI_RUE'=>'Rue du Marché','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000060','UTI_LICENCE'=>'60060','UTI_NOM_UTILISATEUR'=>'nperrier','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>42,'UTI_EMAIL'=>'Guillet.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Guillet','UTI_DATE_NAISSANCE'=>'2003-03-03','UTI_RUE'=>'Rue des Hortensias','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000061','UTI_LICENCE'=>'61061','UTI_NOM_UTILISATEUR'=>'lguillet','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>43,'UTI_EMAIL'=>'Renard.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Renard','UTI_DATE_NAISSANCE'=>'2001-04-04','UTI_RUE'=>'Boulevard de l’Ouest','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000062','UTI_LICENCE'=>'62062','UTI_NOM_UTILISATEUR'=>'hrenard','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>44,'UTI_EMAIL'=>'Henry.Léo@mail.fr','UTI_NOM'=>'Léo','UTI_PRENOM'=>'Henry','UTI_DATE_NAISSANCE'=>'2002-05-05','UTI_RUE'=>'Rue de la Croix','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000063','UTI_LICENCE'=>'63063','UTI_NOM_UTILISATEUR'=>'lhenry','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>45,'UTI_EMAIL'=>'Mallet.Alice@mail.fr','UTI_NOM'=>'Alice','UTI_PRENOM'=>'Mallet','UTI_DATE_NAISSANCE'=>'2003-06-06','UTI_RUE'=>'Rue des Jonquilles','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000064','UTI_LICENCE'=>'64064','UTI_NOM_UTILISATEUR'=>'amallet','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>46,'UTI_EMAIL'=>'Adam.Noah@mail.fr','UTI_NOM'=>'Noah','UTI_PRENOM'=>'Adam','UTI_DATE_NAISSANCE'=>'2000-07-07','UTI_RUE'=>'Rue du Jardin','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000065','UTI_LICENCE'=>'65065','UTI_NOM_UTILISATEUR'=>'nadam','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>47,'UTI_EMAIL'=>'Lucas.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Lucas','UTI_DATE_NAISSANCE'=>'2001-08-08','UTI_RUE'=>'Rue de la Mare','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000066','UTI_LICENCE'=>'66066','UTI_NOM_UTILISATEUR'=>'hlucas','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>48,'UTI_EMAIL'=>'Giraud.Eva@mail.fr','UTI_NOM'=>'Eva','UTI_PRENOM'=>'Giraud','UTI_DATE_NAISSANCE'=>'2002-09-09','UTI_RUE'=>'Avenue des Écoles','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000067','UTI_LICENCE'=>'67067','UTI_NOM_UTILISATEUR'=>'egiraud','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>49,'UTI_EMAIL'=>'Pierre.Lina@mail.fr','UTI_NOM'=>'Lina','UTI_PRENOM'=>'Pierre','UTI_DATE_NAISSANCE'=>'2004-10-10','UTI_RUE'=>'Rue des Peupliers Blancs','UTI_CODE_POSTAL'=>'76000','UTI_VILLE'=>'Rouen','UTI_TELEPHONE'=>'0600000068','UTI_LICENCE'=>'68068','UTI_NOM_UTILISATEUR'=>'lpierre','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
            ['UTI_ID'=>50,'UTI_EMAIL'=>'Martin.Hugo@mail.fr','UTI_NOM'=>'Hugo','UTI_PRENOM'=>'Martin','UTI_DATE_NAISSANCE'=>'2003-11-11','UTI_RUE'=>'Rue de la Source','UTI_CODE_POSTAL'=>'14000','UTI_VILLE'=>'Caen','UTI_TELEPHONE'=>'0600000069','UTI_LICENCE'=>'69069','UTI_NOM_UTILISATEUR'=>'hmartin','UTI_MOT_DE_PASSE'=>Hash::make($plain)],
        ];

        // add timestamps to users if the table has timestamp columns
        if (Schema::hasColumn('VIK_UTILISATEUR', 'created_at') && Schema::hasColumn('VIK_UTILISATEUR', 'updated_at')) {
            foreach ($users as &$u) {
                $u['created_at'] = $u['created_at'] ?? now();
                $u['updated_at'] = $u['updated_at'] ?? now();
            }
            unset($u);
        }
        DB::table('VIK_UTILISATEUR')->insertOrIgnore($users);

        // Reste des inserts dépendant des utilisateurs
        // COUREURS
        $users50 = DB::table('VIK_UTILISATEUR')->whereBetween('UTI_ID', [1, 50])->get();
        $coureurs = [];
        foreach ($users50 as $u) {
            $coureurs[] = [
                'UTI_ID' => $u->UTI_ID,
                'UTI_EMAIL' => $u->UTI_EMAIL,
                'UTI_NOM' => $u->UTI_NOM,
                'UTI_PRENOM' => $u->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $u->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $u->UTI_RUE,
                'UTI_CODE_POSTAL' => $u->UTI_CODE_POSTAL,
                'UTI_VILLE' => $u->UTI_VILLE,
                'UTI_TELEPHONE' => $u->UTI_TELEPHONE,
                'UTI_LICENCE' => $u->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $u->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $u->UTI_MOT_DE_PASSE,
                'CLU_ID' => ($u->UTI_ID % 3) + 1,
                'CRR_PPS' => null,
            ];
        }
        DB::table('VIK_COUREUR')->insertOrIgnore($coureurs);

        // RESPONSABLES CLUB (UTI_ID = 2 -> CLU_ID = 1)
        $respClub = DB::table('VIK_UTILISATEUR')->where('UTI_ID', 2)->first();
        if ($respClub) {
            DB::table('VIK_RESPONSABLE_CLUB')->insertOrIgnore([
                [
                    'UTI_ID' => $respClub->UTI_ID,
                    'UTI_EMAIL' => $respClub->UTI_EMAIL,
                    'UTI_NOM' => $respClub->UTI_NOM,
                    'UTI_PRENOM' => $respClub->UTI_PRENOM,
                    'UTI_DATE_NAISSANCE' => $respClub->UTI_DATE_NAISSANCE,
                    'UTI_RUE' => $respClub->UTI_RUE,
                    'UTI_CODE_POSTAL' => $respClub->UTI_CODE_POSTAL,
                    'UTI_VILLE' => $respClub->UTI_VILLE,
                    'UTI_TELEPHONE' => $respClub->UTI_TELEPHONE,
                    'UTI_LICENCE' => $respClub->UTI_LICENCE,
                    'UTI_NOM_UTILISATEUR' => $respClub->UTI_NOM_UTILISATEUR,
                    'UTI_MOT_DE_PASSE' => $respClub->UTI_MOT_DE_PASSE,
                    'CLU_ID' => 1,
                ],
            ]);
        }

        // RESPONSABLES RAID (UTI_ID in 5,6)
        $respRaidUsers = DB::table('VIK_UTILISATEUR')->whereIn('UTI_ID', [5, 6])->get();
        $respRaid = [];
        foreach ($respRaidUsers as $u) {
            $respRaid[] = [
                'UTI_ID' => $u->UTI_ID,
                'UTI_EMAIL' => $u->UTI_EMAIL,
                'UTI_NOM' => $u->UTI_NOM,
                'UTI_PRENOM' => $u->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $u->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $u->UTI_RUE,
                'UTI_CODE_POSTAL' => $u->UTI_CODE_POSTAL,
                'UTI_VILLE' => $u->UTI_VILLE,
                'UTI_TELEPHONE' => $u->UTI_TELEPHONE,
                'UTI_LICENCE' => $u->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $u->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $u->UTI_MOT_DE_PASSE,
            ];
        }
        DB::table('VIK_RESPONSABLE_RAID')->insertOrIgnore($respRaid);

        // RESPONSABLES COURSE (UTI_ID in 7,8)
        $respCourseUsers = DB::table('VIK_UTILISATEUR')->whereIn('UTI_ID', [7, 8])->get();
        $respCourse = [];
        foreach ($respCourseUsers as $u) {
            $respCourse[] = [
                'UTI_ID' => $u->UTI_ID,
                'UTI_EMAIL' => $u->UTI_EMAIL,
                'UTI_NOM' => $u->UTI_NOM,
                'UTI_PRENOM' => $u->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $u->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $u->UTI_RUE,
                'UTI_CODE_POSTAL' => $u->UTI_CODE_POSTAL,
                'UTI_VILLE' => $u->UTI_VILLE,
                'UTI_TELEPHONE' => $u->UTI_TELEPHONE,
                'UTI_LICENCE' => $u->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $u->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $u->UTI_MOT_DE_PASSE,
            ];
        }
        DB::table('VIK_RESPONSABLE_COURSE')->insertOrIgnore($respCourse);

        // RAIDS
        DB::table('VIK_RAID')->insertOrIgnore([
            ['RAI_ID'=>1,'CLU_ID'=>1,'UTI_ID'=>5,'RAI_NOM'=>'Raid Normandie 2026','RAI_RAID_DATE_DEBUT'=>'2026-03-10 08:00:00','RAI_RAID_DATE_FIN'=>'2026-03-10 18:00:00','RAI_INSCRI_DATE_DEBUT'=>'2026-02-01 00:00:00','RAI_INSCRI_DATE_FIN'=>'2026-03-09 23:59:59','RAI_CONTACT'=>'contact@vikazim.fr','RAI_WEB'=>'www.vikazim.fr','RAI_LIEU'=>'Rouen','RAI_IMAGE'=>'raid1.png'],
            ['RAI_ID'=>2,'CLU_ID'=>2,'UTI_ID'=>6,'RAI_NOM'=>'Raid Caen 2026','RAI_RAID_DATE_DEBUT'=>'2026-04-15 09:00:00','RAI_RAID_DATE_FIN'=>'2026-04-15 17:00:00','RAI_INSCRI_DATE_DEBUT'=>'2026-03-01 00:00:00','RAI_INSCRI_DATE_FIN'=>'2026-04-14 23:59:59','RAI_CONTACT'=>'contact@caenclub.fr','RAI_WEB'=>'www.caenclub.fr','RAI_LIEU'=>'Caen','RAI_IMAGE'=>'raid2.png'],
        ]);

        // COURSES
        DB::table('VIK_COURSE')->insertOrIgnore([
            ['RAI_ID'=>1,'COU_ID'=>1,'TYP_ID'=>1,'DIF_NIVEAU'=>1,'UTI_ID'=>7,'COU_NOM'=>'Course Vitesse Rouen','COU_DATE_DEBUT'=>'2026-03-10 08:30:00','COU_DATE_FIN'=>'2026-03-10 10:30:00','COU_PRIX'=>15.00,'COU_PRIX_ENFANT'=>10.00,'COU_PARTICIPANT_MIN'=>5,'COU_PARTICIPANT_MAX'=>50,'COU_EQUIPE_MIN'=>1,'COU_EQUIPE_MAX'=>10,'COU_PARTICIPANT_PAR_EQUIPE_MAX'=>5,'COU_REPAS_PRIX'=>5.00,'COU_REDUCTION'=>0.0,'COU_LIEU'=>'Forêt de Rouen','COU_AGE_MIN'=>12,'COU_AGE_SEUL'=>16,'COU_AGE_ACCOMPAGNATEUR'=>18],
            ['RAI_ID'=>1,'COU_ID'=>2,'TYP_ID'=>2,'DIF_NIVEAU'=>2,'UTI_ID'=>8,'COU_NOM'=>'Course Endurance Rouen','COU_DATE_DEBUT'=>'2026-03-10 11:00:00','COU_DATE_FIN'=>'2026-03-10 14:00:00','COU_PRIX'=>20.00,'COU_PRIX_ENFANT'=>12.00,'COU_PARTICIPANT_MIN'=>5,'COU_PARTICIPANT_MAX'=>40,'COU_EQUIPE_MIN'=>2,'COU_EQUIPE_MAX'=>8,'COU_PARTICIPANT_PAR_EQUIPE_MAX'=>4,'COU_REPAS_PRIX'=>7.00,'COU_REDUCTION'=>0.0,'COU_LIEU'=>'Forêt de Rouen','COU_AGE_MIN'=>14,'COU_AGE_SEUL'=>18,'COU_AGE_ACCOMPAGNATEUR'=>20],
            ['RAI_ID'=>2,'COU_ID'=>1,'TYP_ID'=>3,'DIF_NIVEAU'=>3,'UTI_ID'=>7,'COU_NOM'=>'Course Relais Caen','COU_DATE_DEBUT'=>'2026-04-15 09:30:00','COU_DATE_FIN'=>'2026-04-15 12:30:00','COU_PRIX'=>25.00,'COU_PRIX_ENFANT'=>15.00,'COU_PARTICIPANT_MIN'=>6,'COU_PARTICIPANT_MAX'=>60,'COU_EQUIPE_MIN'=>2,'COU_EQUIPE_MAX'=>12,'COU_PARTICIPANT_PAR_EQUIPE_MAX'=>5,'COU_REPAS_PRIX'=>8.00,'COU_REDUCTION'=>0.0,'COU_LIEU'=>'Parc de Caen','COU_AGE_MIN'=>16,'COU_AGE_SEUL'=>20,'COU_AGE_ACCOMPAGNATEUR'=>22],
            ['RAI_ID'=>2,'COU_ID'=>2,'TYP_ID'=>4,'DIF_NIVEAU'=>2,'UTI_ID'=>8,'COU_NOM'=>'Course Equipe Caen','COU_DATE_DEBUT'=>'2026-04-15 13:00:00','COU_DATE_FIN'=>'2026-04-15 16:00:00','COU_PRIX'=>18.00,'COU_PRIX_ENFANT'=>10.00,'COU_PARTICIPANT_MIN'=>4,'COU_PARTICIPANT_MAX'=>50,'COU_EQUIPE_MIN'=>2,'COU_EQUIPE_MAX'=>10,'COU_PARTICIPANT_PAR_EQUIPE_MAX'=>4,'COU_REPAS_PRIX'=>6.00,'COU_REDUCTION'=>0.0,'COU_LIEU'=>'Parc de Caen','COU_AGE_MIN'=>12,'COU_AGE_SEUL'=>18,'COU_AGE_ACCOMPAGNATEUR'=>20],
        ]);

        // EQUIPES
        DB::table('VIK_EQUIPE')->insertOrIgnore([
            ['RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>1,'UTI_ID'=>1],['RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>2,'UTI_ID'=>3],['RAI_ID'=>1,'COU_ID'=>2,'EQU_ID'=>1,'UTI_ID'=>5],['RAI_ID'=>2,'COU_ID'=>1,'EQU_ID'=>1,'UTI_ID'=>9],['RAI_ID'=>2,'COU_ID'=>2,'EQU_ID'=>1,'UTI_ID'=>11]
        ]);

        // APPARTENANCE
        DB::table('VIK_APPARTIENT')->insertOrIgnore([
            ['UTI_ID'=>1,'RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>1],['UTI_ID'=>2,'RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>1],
            ['UTI_ID'=>3,'RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>2],['UTI_ID'=>4,'RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>2],
            ['UTI_ID'=>5,'RAI_ID'=>1,'COU_ID'=>2,'EQU_ID'=>1],['UTI_ID'=>6,'RAI_ID'=>1,'COU_ID'=>2,'EQU_ID'=>1],
            ['UTI_ID'=>9,'RAI_ID'=>2,'COU_ID'=>1,'EQU_ID'=>1],['UTI_ID'=>10,'RAI_ID'=>2,'COU_ID'=>1,'EQU_ID'=>1],
            ['UTI_ID'=>11,'RAI_ID'=>2,'COU_ID'=>2,'EQU_ID'=>1],['UTI_ID'=>12,'RAI_ID'=>2,'COU_ID'=>2,'EQU_ID'=>1]
        ]);

        // RESULTATS
        DB::table('VIK_RESULTAT')->insertOrIgnore([
            ['RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>1,'RES_RANG'=> '1','RES_TEMPS'=>'00:45:12','RES_POINT'=>100],
            ['RAI_ID'=>1,'COU_ID'=>1,'EQU_ID'=>2,'RES_RANG'=> '2','RES_TEMPS'=>'00:50:30','RES_POINT'=>90],
            ['RAI_ID'=>1,'COU_ID'=>2,'EQU_ID'=>1,'RES_RANG'=> '1','RES_TEMPS'=>'01:15:00','RES_POINT'=>100],
            ['RAI_ID'=>2,'COU_ID'=>1,'EQU_ID'=>1,'RES_RANG'=> '1','RES_TEMPS'=>'01:10:15','RES_POINT'=>100],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprime uniquement les données que nous avons insérées
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        DB::table('VIK_RESULTAT')->whereIn('RAI_ID', [1,2])->delete();
        DB::table('VIK_APPARTIENT')->whereIn('RAI_ID', [1,2])->delete();
        DB::table('VIK_EQUIPE')->whereIn('RAI_ID', [1,2])->delete();
        DB::table('VIK_COURSE')->whereIn('COU_ID', [1,2])->delete();
        DB::table('VIK_RAID')->whereIn('RAI_ID', [1,2])->delete();

        DB::table('VIK_RESPONSABLE_COURSE')->whereIn('UTI_ID', [7,8])->delete();
        DB::table('VIK_RESPONSABLE_RAID')->whereIn('UTI_ID', [5,6])->delete();
        DB::table('VIK_RESPONSABLE_CLUB')->where('UTI_ID', 2)->delete();

        DB::table('VIK_COUREUR')->whereBetween('UTI_ID', [1,50])->delete();
        DB::table('VIK_ADMINISTRATEUR')->where('UTI_ID', 100)->delete();
        DB::table('VIK_UTILISATEUR')->whereBetween('UTI_ID', [1,50])->delete();
        DB::table('VIK_UTILISATEUR')->where('UTI_ID', 100)->delete();

        DB::table('VIK_TRANCHE_DIFFICULTE')->whereIn('DIF_NIVEAU', [1,2,3,4,5])->delete();
        DB::table('VIK_COURSE_TYPE')->whereIn('TYP_ID', [1,2,3,4,5])->delete();
        DB::table('VIK_CLUB')->whereIn('CLU_ID', [1,2,3])->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};