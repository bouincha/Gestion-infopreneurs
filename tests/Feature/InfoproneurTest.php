<?php

namespace Tests\Feature;

use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InfoproneurTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_pages_render(): void
    {
        $info = Infoproneur::factory()->has(Projet::factory(2))->create();

        $this->get(route('dashboard'))->assertOk()->assertSee($info->nom_complet);
        $this->get(route('infoproneurs.index'))->assertOk()->assertSee($info->nom_complet);
        $this->get(route('infoproneurs.create'))->assertOk();
        $this->get(route('infoproneurs.show', $info))->assertOk()->assertSee($info->projets->first()->titre);
        $this->get(route('infoproneurs.edit', $info))->assertOk();
    }

    public function test_index_can_search_and_filter_by_domaine(): void
    {
        $design = Domaine::factory()->create();
        Infoproneur::factory()->for($design)->create(['nom_complet' => 'Sara Benali']);
        Infoproneur::factory()->create(['nom_complet' => 'Youssef Alaoui']);

        $this->get(route('infoproneurs.index', ['q' => 'Sara']))->assertSee('Sara Benali')->assertDontSee('Youssef Alaoui');
        $this->get(route('infoproneurs.index', ['domaine' => $design->id]))->assertSee('Sara Benali')->assertDontSee('Youssef Alaoui');
    }

    public function test_store_with_photo(): void
    {
        $domaine = Domaine::factory()->create();

        $response = $this->post(route('infoproneurs.store'), [
            'nom_complet' => 'Sara Benali',
            'email' => 'sara@example.com',
            'date_naissance' => '1995-04-12',
            'domaine_id' => $domaine->id,
            'photo' => $this->fakeImage('sara.jpg'),
        ]);

        $info = Infoproneur::firstOrFail();
        $response->assertRedirect(route('infoproneurs.show', $info));
        $this->assertSame($domaine->id, $info->domaine_id);
        Storage::disk('public')->assertExists($info->photo);
        $this->get($info->photo_url)->assertOk();
    }

    public function test_store_validates_input(): void
    {
        $this->post(route('infoproneurs.store'), ['date_naissance' => now()->addDay()->format('Y-m-d')])
            ->assertSessionHasErrors(['nom_complet', 'date_naissance']);
    }

    public function test_update_replaces_and_removes_photo(): void
    {
        $old = $this->fakeImage('old.jpg')->store('photos', 'public');
        $info = Infoproneur::factory()->create(['photo' => $old]);
        $data = ['nom_complet' => 'Nouveau Nom', 'date_naissance' => '1990-01-01'];

        $this->put(route('infoproneurs.update', $info), $data + ['photo' => $this->fakeImage('new.jpg')])
            ->assertRedirect(route('infoproneurs.show', $info));
        Storage::disk('public')->assertMissing($old);
        $new = $info->refresh()->photo;
        Storage::disk('public')->assertExists($new);
        $this->assertSame('Nouveau Nom', $info->nom_complet);

        $this->put(route('infoproneurs.update', $info), $data + ['supprimer_photo' => '1']);
        Storage::disk('public')->assertMissing($new);
        $this->assertNull($info->refresh()->photo);
    }

    public function test_destroy_deletes_photo_and_projects(): void
    {
        $photo = $this->fakeImage('p.jpg')->store('photos', 'public');
        $info = Infoproneur::factory()->has(Projet::factory())->create(['photo' => $photo]);

        $this->delete(route('infoproneurs.destroy', $info))->assertRedirect(route('infoproneurs.index'));

        $this->assertModelMissing($info);
        $this->assertSame(0, Projet::count());
        Storage::disk('public')->assertMissing($photo);
    }
}
