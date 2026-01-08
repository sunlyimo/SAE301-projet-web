<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Raid;
use App\Models\Course;
use App\Models\Club;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseRoutesTest extends TestCase
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
        ]);
    }

    /**
     * Helper pour créer un raid avec un club.
     */
    private function createRaidWithClub($user = null)
    {
        $this->seedReferenceData();

        if (!$user) {
            $user = User::factory()->create();
        }
        $club = Club::factory()->create();

        // Créer l'utilisateur comme responsable de raid et de course
        \DB::table('vik_responsable_raid')->insertOrIgnore([
            'UTI_ID' => $user->UTI_ID,
            'UTI_EMAIL' => $user->UTI_EMAIL,
            'UTI_NOM' => $user->UTI_NOM,
            'UTI_PRENOM' => $user->UTI_PRENOM,
            'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
            'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
            'UTI_RUE' => $user->UTI_RUE,
            'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
            'UTI_VILLE' => $user->UTI_VILLE,
            'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
            'UTI_LICENCE' => $user->UTI_LICENCE,
        ]);

        \DB::table('vik_responsable_course')->insertOrIgnore([
            'UTI_ID' => $user->UTI_ID,
            'UTI_EMAIL' => $user->UTI_EMAIL,
            'UTI_NOM' => $user->UTI_NOM,
            'UTI_PRENOM' => $user->UTI_PRENOM,
            'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
            'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
            'UTI_RUE' => $user->UTI_RUE,
            'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
            'UTI_VILLE' => $user->UTI_VILLE,
            'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
            'UTI_LICENCE' => $user->UTI_LICENCE,
        ]);

        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => $club->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);

        return ['raid' => $raid, 'user' => $user, 'club' => $club];
    }

    /**
     * Test que la page d'index des courses est accessible.
     */
    public function test_courses_index_is_accessible(): void
    {
        $response = $this->get(route('courses.index'));
        $response->assertStatus(200);
    }

    /**
     * Test que la page de création de course nécessite une authentification.
     */
    public function test_courses_create_requires_authentication(): void
    {
        $response = $this->get(route('courses.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la création d'une course nécessite une authentification.
     */
    public function test_courses_store_requires_authentication(): void
    {
        $response = $this->post(route('courses.store'), [
            'nom' => 'Test Course',
        ]);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que mes courses nécessite une authentification.
     */
    public function test_my_courses_requires_authentication(): void
    {
        $response = $this->get(route('courses.my-courses'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test qu'un utilisateur authentifié peut accéder à mes courses.
     */
    public function test_authenticated_user_can_access_my_courses(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('courses.my-courses'));
        $response->assertStatus(200);
    }

    /**
     * Test que la page d'inscription à une course nécessite une authentification.
     */
    public function test_course_inscription_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->get(route('courses.inscription', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la page de modification de course nécessite une authentification.
     */
    public function test_course_edit_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->get(route('courses.edit', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la mise à jour d'une course nécessite une authentification.
     */
    public function test_course_update_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->patch(route('courses.update', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]), [
            'COU_NOM' => 'Updated Course',
        ]);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la suppression d'une course nécessite une authentification.
     */
    public function test_course_destroy_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->delete(route('courses.destroy', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la page de gestion des équipes nécessite une authentification.
     */
    public function test_manage_teams_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->get(route('courses.manage-teams', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la page des résultats nécessite une authentification.
     */
    public function test_resultats_index_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->get(route('resultats.index', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que l'ajout de résultats nécessite une authentification.
     */
    public function test_resultats_store_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->post(route('resultats.store', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]), []);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que l'import de résultats CSV nécessite une authentification.
     */
    public function test_resultats_import_requires_authentication(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->post(route('resultats.import', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]), []);
        $response->assertRedirect(route('login'));
    }
}
