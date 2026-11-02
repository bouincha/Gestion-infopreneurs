<?php

namespace Tests\Feature;

use App\Models\Infoproneur;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_pages_render(): void
    {
        $projet = Projet::factory()->create();

        $this->get(route('projets.index'))->assertOk()->assertSee($projet->titre);
        $this->get(route('projets.create'))->assertOk();
        $this->get(route('projets.show', $projet))->assertOk()->assertSee($projet->infoproneur->nom_complet);
        $this->get(route('projets.edit', $projet))->assertOk();
    }

    public function test_create_page_without_infoproneurs_prompts_to_add_one(): void
    {
        $this->get(route('projets.create'))->assertOk()->assertSee('Ajouter un infoproneur');
    }

    public function test_index_filters_by_statut(): void
    {
        Projet::factory()->create(['titre' => 'Projet actif', 'statut' => 'en_cours']);
        Projet::factory()->create(['titre' => 'Projet fini', 'statut' => 'termine']);

        $this->get(route('projets.index', ['statut' => 'en_cours']))->assertSee('Projet actif')->assertDontSee('Projet fini');
    }

    public function test_crud(): void
    {
        $info = Infoproneur::factory()->create();
        $data = [
            'titre' => 'Formation en ligne',
            'infoproneur_id' => $info->id,
            'statut' => 'en_cours',
            'budget' => '15000',
            'date_debut' => '2026-01-01',
            'date_fin' => '2026-06-30',
        ];

        $this->post(route('projets.store'), $data)->assertSessionHasNoErrors();
        $projet = Projet::firstOrFail();
        $this->assertSame('Formation en ligne', $projet->titre);

        $this->put(route('projets.update', $projet), ['statut' => 'termine'] + $data)->assertRedirect(route('projets.show', $projet));
        $this->assertSame('termine', $projet->refresh()->statut);
        $this->assertSame(100, $projet->progression);

        $this->delete(route('projets.destroy', $projet))->assertRedirect(route('projets.index'));
        $this->assertModelMissing($projet);
    }

    public function test_validation(): void
    {
        $info = Infoproneur::factory()->create();

        $this->post(route('projets.store'), [
            'titre' => '',
            'infoproneur_id' => $info->id,
            'statut' => 'inconnu',
            'date_debut' => '2026-06-01',
            'date_fin' => '2026-01-01',
        ])->assertSessionHasErrors(['titre', 'statut', 'date_fin']);
    }
}
