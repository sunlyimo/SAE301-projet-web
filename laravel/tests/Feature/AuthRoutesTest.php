<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la page de connexion est accessible pour les invités.
     */
    public function test_login_page_is_accessible_for_guests(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    /**
     * Test que la page d'inscription est accessible pour les invités.
     */
    public function test_register_page_is_accessible_for_guests(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    /**
     * Test qu'un utilisateur authentifié est redirigé depuis la page de connexion.
     */
    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));
        $response->assertRedirect('/');
    }

    /**
     * Test qu'un utilisateur authentifié est redirigé depuis la page d'inscription.
     */
    public function test_authenticated_user_is_redirected_from_register_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));
        $response->assertRedirect('/');
    }

    /**
     * Test que la déconnexion nécessite une authentification.
     */
    public function test_logout_requires_authentication(): void
    {
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test qu'un utilisateur authentifié peut se déconnecter.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test que le profil nécessite une authentification.
     */
    public function test_profile_requires_authentication(): void
    {
        $response = $this->get(route('user.profile'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test qu'un utilisateur authentifié peut accéder à son profil.
     */
    public function test_authenticated_user_can_access_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.profile'));
        $response->assertStatus(200);
    }

    /**
     * Test que la mise à jour du profil nécessite une authentification.
     */
    public function test_profile_update_requires_authentication(): void
    {
        $response = $this->patch(route('user.update'), [
            'nom' => 'Nouveau Nom',
        ]);
        $response->assertRedirect(route('login'));
    }
}
