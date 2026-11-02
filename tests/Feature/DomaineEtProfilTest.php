<?php

namespace Tests\Feature;

use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DomaineEtProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_domaine_crud(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('domaines.store'), ['nom' => 'Design', 'couleur' => '#ec4899'])->assertRedirect(route('domaines.index'));
        $domaine = Domaine::firstOrFail();
        $this->get(route('domaines.index'))->assertOk()->assertSee('Design');
        $this->get(route('domaines.edit', $domaine))->assertOk();

        $this->post(route('domaines.store'), ['nom' => 'Design', 'couleur' => 'rouge'])->assertSessionHasErrors(['nom', 'couleur']);

        $this->put(route('domaines.update', $domaine), ['nom' => 'Design UI', 'couleur' => '#ec4899'])->assertSessionHasNoErrors();
        $this->assertSame('Design UI', $domaine->refresh()->nom);

        $info = Infoproneur::factory()->for($domaine)->create();
        $this->delete(route('domaines.destroy', $domaine))->assertRedirect(route('domaines.index'));
        $this->assertModelMissing($domaine);
        $this->assertNull($info->refresh()->domaine_id);
    }

    public function test_profile_update_and_password_change(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['password' => 'ancien-mdp']);
        $this->actingAs($user);

        $this->get(route('profile.edit'))->assertOk();

        $this->put(route('profile.update'), [
            'name' => 'Mohamed',
            'email' => 'mohamed@example.com',
            'avatar' => $this->fakeImage('a.png'),
        ])->assertSessionHasNoErrors();
        $user->refresh();
        $this->assertSame('Mohamed', $user->name);
        Storage::disk('public')->assertExists($user->avatar);

        $this->put(route('profile.password'), [
            'current_password' => 'faux',
            'password' => 'nouveau-mdp',
            'password_confirmation' => 'nouveau-mdp',
        ])->assertSessionHasErrorsIn('password', ['current_password']);

        $this->put(route('profile.password'), [
            'current_password' => 'ancien-mdp',
            'password' => 'nouveau-mdp',
            'password_confirmation' => 'nouveau-mdp',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('nouveau-mdp', $user->refresh()->password));
    }
}
