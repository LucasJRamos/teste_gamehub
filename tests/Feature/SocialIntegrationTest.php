<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SocialIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_follow_and_block_endpoints_update_relationships(): void
    {
        $viewer = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($viewer)
            ->post("/users/{$target->id}/follow")
            ->assertRedirect();

        $this->assertDatabaseHas('follows', [
            'follower_id' => $viewer->id,
            'followed_id' => $target->id,
        ]);

        $this->actingAs($viewer)
            ->post("/users/{$target->id}/block")
            ->assertRedirect('/users');

        $this->assertDatabaseHas('blocks', [
            'blocker_id' => $viewer->id,
            'blocked_id' => $target->id,
        ]);

        $this->assertDatabaseMissing('follows', [
            'follower_id' => $viewer->id,
            'followed_id' => $target->id,
        ]);
    }

    public function test_blocked_user_disappears_from_search_and_profile(): void
    {
        $viewer = User::factory()->create(['username' => 'viewer']);
        $blocked = User::factory()->create(['username' => 'blocked-dev']);
        $visible = User::factory()->create(['username' => 'visible-dev']);

        $this->actingAs($viewer)->post("/users/{$blocked->id}/block");

        $response = $this->actingAs($viewer)
            ->getJson('/users?search=dev')
            ->assertOk();

        $this->assertSame(['visible-dev'], collect($response->json('data'))->pluck('username')->all());
        $this->assertSame(['blocked-dev'], collect($response->json('blocked_users'))->pluck('username')->all());

        $this->actingAs($viewer)
            ->get("/users/{$blocked->id}")
            ->assertNotFound();

        $this->actingAs($viewer)
            ->get('/users')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Users/Index')
                ->has('users.data', 1)
                ->has('blockedUsers', 1));
    }

    public function test_profile_can_be_updated_through_put_request(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/profile', [
                'username' => 'updated-user',
                'email' => 'updated@example.com',
                'data_nascimento' => '2000-01-01',
                'professional_title' => 'Gameplay Programmer',
            ])
            ->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'updated-user',
            'email' => 'updated@example.com',
            'professional_title' => 'Gameplay Programmer',
        ]);
    }

    public function test_dashboard_and_profile_can_return_json_payloads_for_integration_evidence(): void
    {
        $viewer = User::factory()->create(['username' => 'viewer-dev']);
        $target = User::factory()->create(['username' => 'target-dev']);

        $this->actingAs($viewer)
            ->getJson('/dashboard')
            ->assertOk()
            ->assertJsonPath('data.current_user.username', 'viewer-dev')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'current_user' => ['id', 'username', 'email', 'links'],
                    'suggestions',
                ],
            ]);

        $this->actingAs($viewer)
            ->getJson("/users/{$target->id}")
            ->assertOk()
            ->assertJsonPath('data.profile.user.username', 'target-dev')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'profile' => [
                        'user' => ['id', 'username', 'is_following', 'has_blocked', 'links'],
                        'portfolio_items',
                    ],
                    'suggestions',
                ],
            ]);
    }
}
