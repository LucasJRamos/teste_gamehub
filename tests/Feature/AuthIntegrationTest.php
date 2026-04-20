<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_when_trying_to_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_register_and_be_redirected_to_dashboard(): void
    {
        $response = $this->post('/register', [
            'username' => 'devphase2',
            'email' => 'devphase2@example.com',
            'data_nascimento' => '2001-05-10',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'devphase2',
            'email' => 'devphase2@example.com',
        ]);
    }

    public function test_login_returns_structured_json_and_dashboard_is_an_inertia_page(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'username', 'email'],
            ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard/Index')
                ->has('currentUser')
                ->has('suggestions'));
    }
}
