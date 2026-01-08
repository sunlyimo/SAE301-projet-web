<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Raid;
use App\Models\Course;
use App\Models\Equipe;
use App\Models\Club;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamRoutesTest extends TestCase
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
        $this->makeUserCourseResponsable($user);
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
     * Helper pour rendre un utilisateur responsable de course.
     */
    private function makeUserCourseResponsable($user)
    {
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
    }

    /**
     * Test que la page d'une équipe nécessite une authentification.
     */
    public function test_team_show_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->get(route('teams.show', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test qu'un utilisateur authentifié peut voir une équipe.
     */
    public function test_authenticated_user_can_view_team(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->actingAs($user)->get(route('teams.show', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID
        ]));
        $response->assertStatus(200);
    }

    /**
     * Test que l'ajout d'un membre nécessite une authentification.
     */
    public function test_team_add_member_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->post(route('teams.add', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID
        ]), []);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que le retrait d'un membre nécessite une authentification.
     */
    public function test_team_remove_member_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->delete(route('teams.remove', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID,
            'uti_id' => $user->UTI_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que le toggle chef participation nécessite une authentification.
     */
    public function test_team_toggle_chef_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->post(route('teams.toggle-chef', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la mise à jour du RPPS nécessite une authentification.
     */
    public function test_team_update_rpps_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->patch(route('teams.update-rpps', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID,
            'uti_id' => $user->UTI_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la suppression d'une équipe nécessite une authentification.
     */
    public function test_team_destroy_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->delete(route('teams.destroy', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la validation d'une équipe nécessite une authentification.
     */
    public function test_team_validate_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);
        
        $team = Equipe::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => $course->COU_ID,
            'EQU_ID' => 1,
            'UTI_ID' => $user->UTI_ID,
            'EQU_NOM' => 'Test Team',
        ]);

        $response = $this->post(route('teams.validate', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID,
            'equ_id' => $team->EQU_ID
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la création d'une équipe nécessite une authentification.
     */
    public function test_team_create_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->post(route('courses.team.create', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]), [
            'nom' => 'Test Team'
        ]);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que rejoindre une équipe nécessite une authentification.
     */
    public function test_team_join_requires_authentication(): void
    {
        $this->seedReferenceData();
        $user = User::factory()->create();
        $this->makeUserCourseResponsable($user);
        $raid = Raid::create([
            'RAI_NOM' => 'Test Raid',
            'CLU_ID' => Club::factory()->create()->CLU_ID,
            'RAI_RAID_DATE_DEBUT' => now()->addMonth(),
            'RAI_RAID_DATE_FIN' => now()->addMonths(2),
            'RAI_INSCRI_DATE_DEBUT' => now(),
            'RAI_INSCRI_DATE_FIN' => now()->addWeeks(2),
            'RAI_LIEU' => 'Test City',
            'UTI_ID' => $user->UTI_ID,
        ]);
        
        $course = Course::create([
            'RAI_ID' => $raid->RAI_ID,
            'COU_ID' => 1,
            'COU_NOM' => 'Test Course',
            'UTI_ID' => $user->UTI_ID,
            'TYP_ID' => 1,
            'DIF_NIVEAU' => 1,
            'COU_PRIX' => 50.00,
        ]);

        $response = $this->post(route('courses.team.join', [
            'rai_id' => $raid->RAI_ID,
            'cou_id' => $course->COU_ID
        ]), [
            'code' => 'ABC123'
        ]);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la recherche d'utilisateurs nécessite une authentification.
     */
    public function test_users_search_requires_authentication(): void
    {
        $response = $this->get(route('api.users.search'));
        $response->assertRedirect(route('login'));
    }
}
