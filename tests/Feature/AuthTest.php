<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_login_times_are_tracked(): void
    {
        $user = User::factory()->create(['password' => 'secret123', 'last_login_at' => now()->subDay()]);
        $previous = $user->last_login_at;

        $this->post('/login', ['email' => $user->email, 'password' => 'secret123'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $user->refresh();
        $this->assertEquals($previous->timestamp, $user->previous_login_at->timestamp);
        $this->assertTrue($user->last_login_at->isToday());
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'nope'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_protected_pages_require_login(): void
    {
        foreach (['/index', '/infoproneurs', '/infoproneurs/create', '/projets', '/domaines', '/profil'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect('/index');
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
