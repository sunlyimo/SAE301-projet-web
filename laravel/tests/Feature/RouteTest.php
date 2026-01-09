<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la page d'accueil retourne un statut 200.
     */
    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test que la page about retourne un statut 200.
     */
    public function test_about_page_returns_successful_response(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /**
     * Test que la page de contact retourne un statut 200.
     */
    public function test_contact_show_page_returns_successful_response(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * Test que la page raids index retourne un statut 200.
     */
    public function test_raids_index_returns_successful_response(): void
    {
        $response = $this->get('/raids');
        $response->assertStatus(200);
    }

    /**
     * Test que la page courses index retourne un statut 200.
     */
    public function test_courses_index_returns_successful_response(): void
    {
        $response = $this->get('/courses');
        $response->assertStatus(200);
    }

    /**
     * Test qu'une route inexistante retourne 404.
     */
    public function test_nonexistent_route_returns_404(): void
    {
        $response = $this->get('/route-qui-nexiste-pas');
        $response->assertStatus(404);
    }
}
