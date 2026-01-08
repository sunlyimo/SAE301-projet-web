<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Raid;
use App\Models\Club;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RaidRoutesTest extends TestCase
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

        // Créer l'utilisateur comme responsable de raid
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
     * Test que la page d'index des raids est accessible.
     */
    public function test_raids_index_is_accessible(): void
    {
        $response = $this->get(route('raids.index'));
        $response->assertStatus(200);
    }

    /**
     * Test que la page de création de raid nécessite une authentification.
     */
    public function test_raids_create_requires_authentication(): void
    {
        $response = $this->get(route('raids.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la création d'un raid nécessite une authentification.
     */
    public function test_raids_store_requires_authentication(): void
    {
        $response = $this->post(route('raids.store'), [
            'nom' => 'Test Raid',
            'date' => '2026-06-01',
        ]);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test que la page de modification de raid est accessible pour le responsable.
     */
    public function test_raids_edit_is_accessible(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $response = $this->actingAs($user)->get(route('raids.edit', $raid->RAI_ID));
        $response->assertStatus(200);
    }

    /**
     * Test que la mise à jour d'un raid est accessible pour le responsable.
     */
    public function test_raids_update_is_accessible(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];
        $user = $data['user'];

        $response = $this->actingAs($user)->put(route('raids.update', $raid->RAI_ID), [
            'RAI_NOM' => 'Updated Raid',
        ]);
        $response->assertStatus(302);
    }

    /**
     * Test que la page mes raids nécessite une authentification.
     */
    public function test_my_raids_requires_authentication(): void
    {
        $response = $this->get(route('raids.my-raids'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test qu'un utilisateur authentifié peut accéder à la page mes raids.
     */
    public function test_authenticated_user_can_access_my_raids(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('raids.my-raids'));
        $response->assertStatus(200);
    }

    /**
     * Test que les courses d'un raid sont accessibles.
     */
    public function test_raid_courses_are_accessible(): void
    {
        $data = $this->createRaidWithClub();
        $raid = $data['raid'];

        $response = $this->get(route('raids.courses', $raid->RAI_ID));
        $response->assertStatus(200);
    }
}
