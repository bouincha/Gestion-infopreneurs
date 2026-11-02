<?php

namespace Tests\Feature;

use App\Models\Domaine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_is_public(): void
    {
        Domaine::factory()->create(['nom' => 'Coaching']);

        $this->get('/')->assertOk()
            ->assertSee('Gérez vos infopreneurs')
            ->assertSee('Coaching')
            ->assertSee(route('login'));

        $this->get('/index')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertSee('Se connecter')->assertSee("Retour à l'accueil", false);
    }

    public function test_landing_page_links_to_dashboard_when_logged_in(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')->assertOk()->assertSee('Tableau de bord')->assertSee(route('dashboard'));
    }
}
