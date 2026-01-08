<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Raid;
use App\Models\Club;
use App\Models\Course;
use App\Models\Equipe;
use App\Models\Appartient;
use App\Models\TypeCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseOverlapTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour insérer les données de référence nécessaires.
     */
    private function seedReferenceData()
    {
        \DB::table('vik_course_type')->insertOrIgnore([
            ['TYP_ID' => 1, 'TYP_DESCRIPTION' => 'Course de vitesse'],
            ['TYP_ID' => 2, 'TYP_DESCRIPTION' => 'Course d\'endurance'],
        ]);

        \DB::table('vik_tranche_difficulte')->insertOrIgnore([
            ['DIF_NIVEAU' => 1, 'DIF_DESCRIPTION' => 'Débutant'],
            ['DIF_NIVEAU' => 2, 'DIF_DESCRIPTION' => 'Intermédiaire'],
            ['DIF_NIVEAU' => 3, 'DIF_DESCRIPTION' => 'Avancé'],
        ]);
    }

    /**
     * Helper pour créer un responsable de course
     */
    private function createCourseResponsable(User $user)
    {
        \DB::table('vik_responsable_course')->insert([
            'UTI_ID' => $user->UTI_ID,
            'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
            'UTI_EMAIL' => $user->UTI_EMAIL,
            'UTI_NOM' => $user->UTI_NOM,
            'UTI_PRENOM' => $user->UTI_PRENOM,
            'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
            'UTI_RUE' => $user->UTI_RUE,
            'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
            'UTI_VILLE' => $user->UTI_VILLE,
            'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
            'UTI_LICENCE' => $user->UTI_LICENCE,
            'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
        ]);
    }

    /**
     * Test qu'un utilisateur ne peut pas créer une équipe pour une course qui chevauche une autre
     */
    public function test_user_cannot_create_team_for_overlapping_course()
    {
        $this->seedReferenceData();

        // Créer un utilisateur et un club
        $user = User::factory()->create();
        $club = Club::factory()->create();

        // Créer le responsable de course
        $this->createCourseResponsable($user);

        // Créer un raid
        $raid = Raid::create([
            'CLU_ID' => $club->CLU_ID,
            'UTI_ID' => $user->UTI_ID,
            'RAI_NOM' => 'Raid Test',
            'RAI_RAID_DATE_DEBUT' => '2025-06-01 08:00:00',
            'RAI_RAID_DATE_FIN' => '2025-06-01 18:00:00',
            'RAI_INSCRI_DATE_DEBUT' => '2025-05-01 00:00:00',
            'RAI_INSCRI_DATE_FIN' => '2025-05-31 23:59:59',
            'RAI_CONTACT' => $user->UTI_EMAIL,
            'RAI_LIEU' => 'Test Location',
        ]);

        // Créer deux courses avec horaires qui se chevauchent
        $course1 = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 3,
            'UTI_ID' => $user->UTI_ID,
            'COU_NOM' => 'Course 1',
            'COU_DATE_DEBUT' => '2025-06-01 10:00:00',
            'COU_DATE_FIN' => '2025-06-01 14:00:00',
            'COU_PRIX' => 20,
            'COU_LIEU' => 'Lieu 1',
            'COU_AGE_MIN' => 12,
            'COU_AGE_SEUL' => 16,
            'COU_PARTICIPANT_MIN' => 2,
            'COU_PARTICIPANT_MAX' => 100,
            'COU_EQUIPE_MIN' => 1,
            'COU_EQUIPE_MAX' => 50,
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 5,
        ]);

        $course2 = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 2,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 3,
            'UTI_ID' => $user->UTI_ID,
            'COU_NOM' => 'Course 2',
            'COU_DATE_DEBUT' => '2025-06-01 13:00:00', // Chevauche course1
            'COU_DATE_FIN' => '2025-06-01 18:00:00',
            'COU_PRIX' => 20,
            'COU_LIEU' => 'Lieu 2',
            'COU_AGE_MIN' => 12,
            'COU_AGE_SEUL' => 16,
            'COU_PARTICIPANT_MIN' => 2,
            'COU_PARTICIPANT_MAX' => 100,
            'COU_EQUIPE_MIN' => 1,
            'COU_EQUIPE_MAX' => 50,
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 5,
        ]);

        // Créer une équipe pour la course 1
        $equipe1 = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course1->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Équipe 1',
        ]);

        // L'utilisateur est maintenant inscrit à la course 1 (en tant que chef)

        // Tenter de créer une équipe pour la course 2 (qui chevauche)
        $response = $this->actingAs($user)->post(route('courses.team.create', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course2->COU_ID
        ]), [
            'EQU_NOM' => 'Équipe 2'
        ]);

        // Vérifier que la création est refusée avec un message d'erreur
        $response->assertSessionHasErrors('overlap');
        $this->assertStringContainsString('Course 1', session('errors')->first('overlap'));
    }

    /**
     * Test qu'un utilisateur ne peut pas rejoindre une équipe pour une course qui chevauche une autre
     */
    public function test_user_cannot_join_team_for_overlapping_course()
    {
        $this->seedReferenceData();

        // Créer deux utilisateurs
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $club = Club::factory()->create();

        // Créer les responsables de course
        $this->createCourseResponsable($user1);

        // Créer un raid
        $raid = Raid::create([
            'CLU_ID' => $club->CLU_ID,
            'UTI_ID' => $user1->UTI_ID,
            'RAI_NOM' => 'Raid Test 2',
            'RAI_RAID_DATE_DEBUT' => '2025-06-01 08:00:00',
            'RAI_RAID_DATE_FIN' => '2025-06-01 18:00:00',
            'RAI_INSCRI_DATE_DEBUT' => '2025-05-01 00:00:00',
            'RAI_INSCRI_DATE_FIN' => '2025-05-31 23:59:59',
            'RAI_CONTACT' => $user1->UTI_EMAIL,
            'RAI_LIEU' => 'Test Location 2',
        ]);

        // Créer deux courses avec horaires qui se chevauchent
        $course1 = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 3,
            'UTI_ID' => $user1->UTI_ID,
            'COU_NOM' => 'Course A',
            'COU_DATE_DEBUT' => '2025-06-01 10:00:00',
            'COU_DATE_FIN' => '2025-06-01 14:00:00',
            'COU_PRIX' => 20,
            'COU_LIEU' => 'Lieu A',
            'COU_AGE_MIN' => 12,
            'COU_AGE_SEUL' => 16,
            'COU_PARTICIPANT_MIN' => 2,
            'COU_PARTICIPANT_MAX' => 100,
            'COU_EQUIPE_MIN' => 1,
            'COU_EQUIPE_MAX' => 50,
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 5,
        ]);

        $course2 = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 2,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 3,
            'UTI_ID' => $user1->UTI_ID,
            'COU_NOM' => 'Course B',
            'COU_DATE_DEBUT' => '2025-06-01 12:00:00', // Chevauche course1
            'COU_DATE_FIN' => '2025-06-01 16:00:00',
            'COU_PRIX' => 20,
            'COU_LIEU' => 'Lieu B',
            'COU_AGE_MIN' => 12,
            'COU_AGE_SEUL' => 16,
            'COU_PARTICIPANT_MIN' => 2,
            'COU_PARTICIPANT_MAX' => 100,
            'COU_EQUIPE_MIN' => 1,
            'COU_EQUIPE_MAX' => 50,
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 5,
        ]);

        // user2 est déjà inscrit à la course 1
        $equipe1 = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course1->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user1->UTI_ID,
            'EQU_NOM' => 'Équipe Alpha',
        ]);

        // Créer un coureur pour user2
        \DB::table('vik_coureur')->insert([
            'UTI_ID' => $user2->UTI_ID,
            'UTI_NOM_UTILISATEUR' => $user2->UTI_NOM_UTILISATEUR,
            'UTI_EMAIL' => $user2->UTI_EMAIL,
            'UTI_NOM' => $user2->UTI_NOM,
            'UTI_PRENOM' => $user2->UTI_PRENOM,
            'UTI_DATE_NAISSANCE' => $user2->UTI_DATE_NAISSANCE,
            'UTI_RUE' => $user2->UTI_RUE,
            'UTI_CODE_POSTAL' => $user2->UTI_CODE_POSTAL,
            'UTI_VILLE' => $user2->UTI_VILLE,
            'UTI_TELEPHONE' => $user2->UTI_TELEPHONE,
            'UTI_LICENCE' => $user2->UTI_LICENCE,
            'UTI_MOT_DE_PASSE' => $user2->UTI_MOT_DE_PASSE,
        ]);

        Appartient::create([
            'UTI_ID' => $user2->UTI_ID,
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course1->COU_ID,
            'EQU_ID' => $equipe1->EQU_ID,
        ]);

        // user1 crée une équipe pour la course 2
        $equipe2 = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course2->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user1->UTI_ID,
            'EQU_NOM' => 'Équipe Beta',
        ]);

        // user2 tente de rejoindre l'équipe 2 (course qui chevauche)
        $response = $this->actingAs($user2)->post(route('courses.team.join', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course2->COU_ID
        ]), [
            'EQU_ID' => $equipe2->EQU_ID
        ]);

        // Vérifier que l'inscription est refusée
        $response->assertSessionHasErrors('overlap');
        $this->assertStringContainsString('Course A', session('errors')->first('overlap'));
    }

    /**
     * Test qu'un utilisateur peut créer une équipe pour une course qui ne chevauche pas
     */
    public function test_user_can_create_team_for_non_overlapping_course()
    {
        $this->seedReferenceData();

        $user = User::factory()->create();
        $club = Club::factory()->create();

        // Créer le responsable de course
        $this->createCourseResponsable($user);

        $raid = Raid::create([
            'CLU_ID' => $club->CLU_ID,
            'UTI_ID' => $user->UTI_ID,
            'RAI_NOM' => 'Raid Test 3',
            'RAI_RAID_DATE_DEBUT' => '2025-06-01 08:00:00',
            'RAI_RAID_DATE_FIN' => '2025-06-01 20:00:00',
            'RAI_INSCRI_DATE_DEBUT' => '2025-05-01 00:00:00',
            'RAI_INSCRI_DATE_FIN' => '2025-05-31 23:59:59',
            'RAI_CONTACT' => $user->UTI_EMAIL,
            'RAI_LIEU' => 'Test Location 3',
        ]);

        // Deux courses qui ne se chevauchent PAS
        $course1 = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 3,
            'UTI_ID' => $user->UTI_ID,
            'COU_NOM' => 'Course Matin',
            'COU_DATE_DEBUT' => '2025-06-01 08:00:00',
            'COU_DATE_FIN' => '2025-06-01 12:00:00',
            'COU_PRIX' => 20,
            'COU_LIEU' => 'Lieu 1',
            'COU_AGE_MIN' => 12,
            'COU_AGE_SEUL' => 16,
            'COU_PARTICIPANT_MIN' => 2,
            'COU_PARTICIPANT_MAX' => 100,
            'COU_EQUIPE_MIN' => 1,
            'COU_EQUIPE_MAX' => 50,
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 5,
        ]);

        $course2 = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 2,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 3,
            'UTI_ID' => $user->UTI_ID,
            'COU_NOM' => 'Course Après-midi',
            'COU_DATE_DEBUT' => '2025-06-01 14:00:00', // Pas de chevauchement
            'COU_DATE_FIN' => '2025-06-01 18:00:00',
            'COU_PRIX' => 20,
            'COU_LIEU' => 'Lieu 2',
            'COU_AGE_MIN' => 12,
            'COU_AGE_SEUL' => 16,
            'COU_PARTICIPANT_MIN' => 2,
            'COU_PARTICIPANT_MAX' => 100,
            'COU_EQUIPE_MIN' => 1,
            'COU_EQUIPE_MAX' => 50,
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 5,
        ]);

        // Créer une équipe pour la course 1
        $equipe1 = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course1->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Équipe Matin',
        ]);

        // Tenter de créer une équipe pour la course 2 (qui ne chevauche pas)
        $response = $this->actingAs($user)->post(route('courses.team.create', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course2->COU_ID
        ]), [
            'EQU_NOM' => 'Équipe Après-midi'
        ]);

        // Vérifier que la création réussit (pas d'erreur)
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // Vérifier que l'équipe a bien été créée
        $this->assertDatabaseHas('vik_equipe', [
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course2->COU_ID,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Équipe Après-midi'
        ]);
    }
}
