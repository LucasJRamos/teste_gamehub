<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_redirects_to_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_guest_can_view_authentication_forms(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('GAME HUB');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('CADASTRO');
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $this->post(route('register'), [
            'username' => 'player_one',
            'email' => 'player@example.com',
            'data_nascimento' => '2000-01-15',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user = User::where('email', 'player@example.com')->firstOrFail();

        $this->assertSame('player_one', $user->username);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $this->post(route('login'), [
            'email' => 'login@example.com',
            'password' => 'secret123',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'invalid-login@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $this->post(route('login'), [
            'email' => 'invalid-login@example.com',
            'password' => 'wrong-password',
        ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create(['username' => 'DevMaster']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('DevMaster');
    }
}
