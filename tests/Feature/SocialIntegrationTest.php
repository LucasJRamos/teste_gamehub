<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->actingAs($viewer)
            ->getJson('/users?search=dev')
            ->assertOk()
            ->assertJsonFragment(['username' => 'visible-dev'])
            ->assertJsonMissing(['username' => 'blocked-dev']);

        $this->actingAs($viewer)
            ->get("/users/{$blocked->id}")
            ->assertNotFound();
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
}
