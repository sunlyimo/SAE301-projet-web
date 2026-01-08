<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SoutenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Désactivation des contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clubs supplémentaires
        DB::table('vik_club')->insert([
            ['CLU_ID' => 4, 'CLU_NOM' => 'CO Azimut 77', 'CLU_RUE' => '24 Rue de la Rochette', 'CLU_CODE_POSTAL' => '77000', 'CLU_VILLE' => 'Melun'],
            ['CLU_ID' => 5, 'CLU_NOM' => 'Raidlinks', 'CLU_RUE' => '14 Place des Terrasses de l\'Agora', 'CLU_CODE_POSTAL' => '91000', 'CLU_VILLE' => 'EVRY'],
            ['CLU_ID' => 6, 'CLU_NOM' => 'Balise 25', 'CLU_RUE' => '2 Avenue Léo Lagrange', 'CLU_CODE_POSTAL' => '25000', 'CLU_VILLE' => 'Besançon'],
            ['CLU_ID' => 7, 'CLU_NOM' => 'VIKAZIM', 'CLU_RUE' => '28 rue des bleuets', 'CLU_CODE_POSTAL' => '14000', 'CLU_VILLE' => 'CAEN'],
        ]);

        // Utilisateurs supplémentaires
        $utilisateurs = [
            [51, 'julien.martin@unicaen.fr', 'MARTIN', 'Julien', '1990-04-15', '12 rue des sports', '77000', 'Melun', '0612345678', '77001234', 'jmartin', 'pass123'],
            [52, 'claire.dumont@test.fr', 'DUMONT', 'Clara', '1985-09-22', '45 rue des plantes', '14123', 'IFS', '0698765432', '25004567', 'cdumont', 'pass123'],
            [53, 'antoine.petit@test.fr', 'PETIT', 'Antoine', '2002-03-01', '5 chemin du Lac', '25140', 'Charquemont', '25140', 'Charquemont', '0711223344', '2025-T11LF3', 'antoine.petit', 'pass123'],
            [54, 'sandra.marveli@test.fr', 'MARVELI', 'Sandra', '1995-07-18', '8 bis rue du Parc', '14400', 'BAYEUX', '0655443322', '64006678', 'sandra.marveli', 'pass123'],
            [55, 'lucas.bernard@test.fr', 'BERNARD', 'Lucas', '1988-01-11', '3 allée des Sports', '91002', 'EVRY', '0766778899', '91002345', 'lucas.bernard', 'pass123'],
            [56, 'c.dumont@email.fr', 'DUPONT', 'Claire', '1992-05-14', '12 rue des Pins', '77100', 'MEAUX', '0612457890', '1204558', 'c.dumont', 'pass123'],
            [57, 't.lefebvre@orange.fr', 'LEFEBVRE', 'Thomas', '1985-11-23', '21 route de Collège', '91300', 'Montbéliard', '0654892133', '2298741', 't.lefebvre', 'pass123'],
            [58, 'sophie.m60@wanadoo.fr', 'MOREAU', 'Sophie', '2001-02-02', '45 chemin du Bonnet', '77000', 'Melun', '0781024456', '6003214', 'sophie.m60', 'pass123'],
            [59, 'thomas.leroy@gmail.com', 'LEROY', 'Thomas', '1995-08-30', '102 rue du Moulin', '77500', 'Chelles', '0633571288', '6901122', 'thomas.leroy', 'pass123'],
            [60, 'julie.garnier@outlook.com', 'GARNIER', 'Julie', '1988-07-12', '3 place de la Mairie', '77000', 'Melun', '0765901122', '6700548', 'julie.garnier', 'pass123'],
            [61, 'm.rousseau@sfr.fr', 'ROUSSEAU', 'Marc', '1974-01-19', '2 rue de la Poste', '77000', 'Melun', '0609883451', '6700548', 'm.rousseau', 'pass123'],
            [62, 'hugo.fontaine@test.fr', 'FONTAINE', 'Hugo', '2003-10-05', '6 rue du Collège', '25200', 'Mons', '0673849516', '91006754', 'hugo.fontaine', 'pass123'],
            [63, 'lea.caron@test.fr', 'CARON', 'Lea', '1990-04-27', '4 rue des Esses', '25140', 'Montbéliard', '0614253647', '77009876', 'lea.caron', 'pass123'],
            [64, 'emma.petit@test.fr', 'PETIT', 'Emma', '2005-12-08', '4 rue des Esses', '25140', 'Montbéliard', '0621436587', '77009876', 'emma.petit', 'pass123'],
            [65, 'nathan.roux@test.fr', 'ROUX', 'Nathan', '2000-06-26', '16 chemin Vert', '25400', 'Audincourt', '0734567812', '25006789', 'nathan.roux', 'pass123'],
            [66, 'paul.dorbec@unicaen.fr', 'DORBEC', 'Paul', '1980-04-02', '22 rue des roses', '77000', 'Melun', '0743672311', '23456789', 'paul.dorbec', 'pass123'],
            [67, 'julie.jacquier@unicaen.fr', 'JACQUIER', 'Yohann', '2013-06-03', '35 rue des roses', '14123', 'IFS', '0642864628', '1234567890', 'julie.jacquier', 'pass123'],
            [68, 'sylvian.delhoumi@unicaen.fr', 'DELHOUMI', 'Sylvian', '1985-06-02', '47 rue des chênes', '14000', 'Caen', '0705324567', '2025-D2SI13', 'sylvian.delhoumi', 'pass123'],
            [69, 'jeanfrancois.anne@unicaen.fr', 'ANNE', 'Jean-François', '1964-11-05', '27 rue des tilleuls', '14123', 'Cormeilles Le Royal', '0645389485', '56723478', 'jeanfrancois.anne', 'pass123'],
            [70, 'marc.rousseau@test.fr', 'ROUSSEAU', 'Marc', '1990-01-01', 'Place de la Liberté', '14000', 'Caen', '0600000070', '70070', 'marc.rousseau', 'pass123'],
            [100, 'admin.vikazim@mail.fr', 'Admin', 'Vikazim', '2006-10-16', 'Rue des lilas', '76000', 'Caen', '0600000070', '70070', 'admin_sys', 'Root123!'],
        ];

        foreach ($utilisateurs as $user) {
            DB::table('vik_utilisateur')->insert([
                'UTI_ID' => $user[0],
                'UTI_EMAIL' => $user[1],
                'UTI_NOM' => $user[2],
                'UTI_PRENOM' => $user[3],
                'UTI_DATE_NAISSANCE' => $user[4],
                'UTI_RUE' => $user[5],
                'UTI_CODE_POSTAL' => $user[6],
                'UTI_VILLE' => $user[7],
                'UTI_TELEPHONE' => $user[8],
                'UTI_LICENCE' => $user[9],
                'UTI_NOM_UTILISATEUR' => $user[10],
                'UTI_MOT_DE_PASSE' => Hash::make($user[11]),
            ]);
        }

        $pass = Hash::make('Root123!');
        // Ensure admin user exists (create or update) with UTI_ID = 100
        DB::table('vik_utilisateur')->updateOrInsert(
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

        DB::table('vik_administrateur')->updateOrInsert(
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



        // Coureurs supplémentaires
        for ($id = 51; $id <= 70; $id++) {
            $user = DB::table('vik_utilisateur')->where('UTI_ID', $id)->first();
            DB::table('vik_coureur')->insert([
                'UTI_ID' => $user->UTI_ID,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
                'CLU_ID' => 4,
                'CRR_PPS' => null,
            ]);
        }

        // Responsables RAID
        foreach ([66, 56] as $id) {
            $user = DB::table('vik_utilisateur')->where('UTI_ID', $id)->first();
            DB::table('vik_responsable_raid')->insert([
                'UTI_ID' => $user->UTI_ID,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);
        }

        // Responsables COURSE
        foreach ([51, 66, 56, 62, 70] as $id) {
            $user = DB::table('vik_utilisateur')->where('UTI_ID', $id)->first();
            DB::table('vik_responsable_course')->insert([
                'UTI_ID' => $user->UTI_ID,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);
        }

        // Responsables CLUB
        foreach ([56, 53, 55, 69] as $id) {
            $user = DB::table('vik_utilisateur')->where('UTI_ID', $id)->first();

            // Déterminer le CLU_ID en fonction de l'utilisateur
            $clubId = match($id) {
                56 => 4, // CO Azimut 77 - DUPONT Claire
                53 => 6, // Balise 25 - PETIT Antoine
                55 => 5, // Raidlinks - BERNARD Lucas
                69 => 7, // VIKAZIM - ANNE Jean-François
            };

            DB::table('vik_responsable_club')->insert([
                'UTI_ID' => $user->UTI_ID,
                'CLU_ID' => $clubId,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);
        }

        // RAIDS
        DB::table('vik_raid')->insert([
            [
                'RAI_ID' => 100,
                'CLU_ID' => 4,
                'UTI_ID' => 66,
                'RAI_NOM' => 'Raid CHAMPETRE',
                'RAI_RAID_DATE_DEBUT' => '2025-11-13 00:00:00',
                'RAI_RAID_DATE_FIN' => '2025-11-14 18:00:00',
                'RAI_INSCRI_DATE_DEBUT' => '2025-08-10 00:00:00',
                'RAI_INSCRI_DATE_FIN' => '2025-10-30 23:59:59',
                'RAI_CONTACT' => 'paul.dorbec@unicaen.fr',
                'RAI_WEB' => 'www.coazimut77.fr',
                'RAI_LIEU' => 'PARC INTERCOMMUNAL DEBREUIL 77000 MELUN',
                'RAI_IMAGE' => 'raid_champetre.png',
            ],
            [
                'RAI_ID' => 101,
                'CLU_ID' => 4,
                'UTI_ID' => 56,
                'RAI_NOM' => 'Raid O\'Bivwak',
                'RAI_RAID_DATE_DEBUT' => '2026-05-23 10:00:00',
                'RAI_RAID_DATE_FIN' => '2026-05-24 18:00:00',
                'RAI_INSCRI_DATE_DEBUT' => '2026-01-10 00:00:00',
                'RAI_INSCRI_DATE_FIN' => '2026-04-30 23:59:59',
                'RAI_CONTACT' => 'c.dumont@email.fr',
                'RAI_WEB' => 'www.coazimut77.fr',
                'RAI_LIEU' => 'Parc des Noues - 7 boulevard de la République 77130 MONTERAUT',
                'RAI_IMAGE' => 'raid_obivwak.png',
            ],
        ]);

        // COURSES pour RAID CHAMPETRE
        DB::table('vik_course')->insert([
            [
                'RAI_ID' => 100,
                'COU_ID' => 1,
                'TYP_ID' => 1,
                'DIF_NIVEAU' => 1,
                'UTI_ID' => 51,
                'COU_NOM' => 'Course LUTIN',
                'COU_DATE_DEBUT' => '2025-11-13 10:00:00',
                'COU_DATE_FIN' => '2025-11-13 18:00:00',
                'COU_PRIX' => 0.00,
                'COU_PRIX_ENFANT' => 0.00,
                'COU_PARTICIPANT_MIN' => 2,
                'COU_PARTICIPANT_MAX' => 8,
                'COU_EQUIPE_MIN' => 3,
                'COU_EQUIPE_MAX' => null,
                'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 3,
                'COU_REPAS_PRIX' => 0.00,
                'COU_REDUCTION' => 0.00,
                'COU_LIEU' => 'PARC INTERCOMMUNAL DEBREUIL 77000 MELUN',
                'COU_AGE_MIN' => 12,
                'COU_AGE_SEUL' => null,
                'COU_AGE_ACCOMPAGNATEUR' => null,
            ],
            [
                'RAI_ID' => 100,
                'COU_ID' => 2,
                'TYP_ID' => 2,
                'DIF_NIVEAU' => 2,
                'UTI_ID' => 66,
                'COU_NOM' => 'Course ELFE',
                'COU_DATE_DEBUT' => '2025-11-14 05:00:00',
                'COU_DATE_FIN' => '2025-11-14 18:00:00',
                'COU_PRIX' => 0.00,
                'COU_PRIX_ENFANT' => 0.00,
                'COU_PARTICIPANT_MIN' => 2,
                'COU_PARTICIPANT_MAX' => 8,
                'COU_EQUIPE_MIN' => 4,
                'COU_EQUIPE_MAX' => null,
                'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 4,
                'COU_REPAS_PRIX' => 0.00,
                'COU_REDUCTION' => 0.00,
                'COU_LIEU' => 'PARC INTERCOMMUNAL DEBREUIL 77000 MELUN',
                'COU_AGE_MIN' => 18,
                'COU_AGE_SEUL' => null,
                'COU_AGE_ACCOMPAGNATEUR' => null,
            ],
        ]);

        // COURSES pour RAID O'BIVWAK
        DB::table('vik_course')->insert([
            [
                'RAI_ID' => 101,
                'COU_ID' => 1,
                'TYP_ID' => 3,
                'DIF_NIVEAU' => 4,
                'UTI_ID' => 70,
                'COU_NOM' => 'Parcours A',
                'COU_DATE_DEBUT' => '2026-05-23 06:30:00',
                'COU_DATE_FIN' => '2026-05-23 20:00:00',
                'COU_PRIX' => 0.00,
                'COU_PRIX_ENFANT' => 0.00,
                'COU_PARTICIPANT_MIN' => 10,
                'COU_PARTICIPANT_MAX' => 40,
                'COU_EQUIPE_MIN' => 20,
                'COU_EQUIPE_MAX' => null,
                'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 2,
                'COU_REPAS_PRIX' => 0.00,
                'COU_REDUCTION' => 0.00,
                'COU_LIEU' => 'Parc des Noues - 7 boulevard de la République 77130 MONTERAUT',
                'COU_AGE_MIN' => 21,
                'COU_AGE_SEUL' => null,
                'COU_AGE_ACCOMPAGNATEUR' => null,
            ],
            [
                'RAI_ID' => 101,
                'COU_ID' => 2,
                'TYP_ID' => 3,
                'DIF_NIVEAU' => 2,
                'UTI_ID' => 56,
                'COU_NOM' => 'Parcours B',
                'COU_DATE_DEBUT' => '2026-05-24 04:00:00',
                'COU_DATE_FIN' => '2026-05-24 18:00:00',
                'COU_PRIX' => 0.00,
                'COU_PRIX_ENFANT' => 0.00,
                'COU_PARTICIPANT_MIN' => 2,
                'COU_PARTICIPANT_MAX' => 8,
                'COU_EQUIPE_MIN' => 4,
                'COU_EQUIPE_MAX' => null,
                'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 4,
                'COU_REPAS_PRIX' => 0.00,
                'COU_REDUCTION' => 0.00,
                'COU_LIEU' => 'Parc des Noues - 7 boulevard de la République 77130 MONTERAUT',
                'COU_AGE_MIN' => 18,
                'COU_AGE_SEUL' => null,
                'COU_AGE_ACCOMPAGNATEUR' => null,
            ],
        ]);

        // EQUIPES pour RAID CHAMPETRE
        $equipes = [
            // Course LUTIN
            [100, 1, 1, 'Les Balises Furtives', 69, [69, 54, 68]],
            [100, 1, 2, 'Les Traqueurs du Nord', 55, [55, 58]],
            [100, 1, 3, 'Cap sur l\'Azimut', 66, [66, 60]],
            // Course ELFE
            [100, 2, 1, 'Black Compass', 56, [56, 59]],
            [100, 2, 2, 'Strike Balise', 69, [69, 54, 55]],
            [100, 2, 3, 'Silver Meridian', 62, [62, 64]],
            [100, 2, 4, 'Elite Azimut', 65, [65, 53]],
        ];

        foreach ($equipes as $equipe) {
            DB::table('vik_equipe')->insert([
                'RAI_ID' => $equipe[0],
                'COU_ID' => $equipe[1],
                'EQU_ID' => $equipe[2],
                'EQU_NOM' => $equipe[3],
                'UTI_ID' => $equipe[4],
            ]);

            foreach ($equipe[5] as $membre) {
                DB::table('vik_appartient')->insert([
                    'UTI_ID' => $membre,
                    'RAI_ID' => $equipe[0],
                    'COU_ID' => $equipe[1],
                    'EQU_ID' => $equipe[2],
                ]);
            }
        }

        // EQUIPES pour RAID O'BIVWAK Parcours A (pas de données dans les images)

        // EQUIPES pour RAID O'BIVWAK Parcours B
        $equipesObivwak = [
            [101, 2, 1, 'Equipe DORMEUR', 56, [64, 57]],
            [101, 2, 2, 'Equipe ATCHOUM', 62, [62, 63]],
            [101, 2, 3, 'Equipe SIMPLET', 62, [60, 61]],
        ];

        foreach ($equipesObivwak as $equipe) {
            DB::table('vik_equipe')->insert([
                'RAI_ID' => $equipe[0],
                'COU_ID' => $equipe[1],
                'EQU_ID' => $equipe[2],
                'EQU_NOM' => $equipe[3],
                'UTI_ID' => $equipe[4],
            ]);

            foreach ($equipe[5] as $membre) {
                DB::table('vik_appartient')->insert([
                    'UTI_ID' => $membre,
                    'RAI_ID' => $equipe[0],
                    'COU_ID' => $equipe[1],
                    'EQU_ID' => $equipe[2],
                ]);
            }
        }

        // RESULTATS pour RAID CHAMPETRE - Course LUTIN
        DB::table('vik_resultat')->insert([
            [
                'RAI_ID' => 100,
                'COU_ID' => 1,
                'EQU_ID' => 1,
                'RES_POINT' => 199,
                'RES_TEMPS' => '02:45:00',
                'RES_RANG' => 1,
            ],
            [
                'RAI_ID' => 100,
                'COU_ID' => 1,
                'EQU_ID' => 3,
                'RES_POINT' => 157,
                'RES_TEMPS' => '03:14:34',
                'RES_RANG' => 2,
            ],
            [
                'RAI_ID' => 100,
                'COU_ID' => 1,
                'EQU_ID' => 2,
                'RES_POINT' => 145,
                'RES_TEMPS' => '03:01:25',
                'RES_RANG' => 3,
            ],
        ]);

        // RESULTATS pour RAID CHAMPETRE - Course ELFE
        DB::table('vik_resultat')->insert([
            [
                'RAI_ID' => 100,
                'COU_ID' => 2,
                'EQU_ID' => 3,
                'RES_POINT' => 322,
                'RES_TEMPS' => '05:23:25',
                'RES_RANG' => 1,
            ],
            [
                'RAI_ID' => 100,
                'COU_ID' => 2,
                'EQU_ID' => 4,
                'RES_POINT' => 322,
                'RES_TEMPS' => '05:33:56',
                'RES_RANG' => 2,
            ],
            [
                'RAI_ID' => 100,
                'COU_ID' => 2,
                'EQU_ID' => 2,
                'RES_POINT' => 287,
                'RES_TEMPS' => '05:47:22',
                'RES_RANG' => 3,
            ],
            [
                'RAI_ID' => 100,
                'COU_ID' => 2,
                'EQU_ID' => 1,
                'RES_POINT' => 199,
                'RES_TEMPS' => '06:21:35',
                'RES_RANG' => 4,
            ],
        ]);

        // Réactivation des contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if ($this->command) {
            $this->command->info('Données de soutenance insérées avec succès !');
        }
    }
}
