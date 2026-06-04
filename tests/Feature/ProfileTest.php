<?php

namespace Tests\Feature;

use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_own_profile(): void
    {
        $user = User::factory()->create([
            'username' => 'game_creator',
            'professional_title' => 'Game Designer',
        ]);

        PortfolioItem::create([
            'user_id' => $user->id,
            'title' => 'Arena Prototype',
            'description' => 'Prototipo de arena multiplayer.',
            'type' => 'link',
            'link_url' => 'https://github.com/example/arena',
        ]);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('game_creator')
            ->assertSee('Game Designer')
            ->assertSee('Arena Prototype');
    }

    public function test_authenticated_user_can_view_edit_profile_form(): void
    {
        $user = User::factory()->create(['username' => 'editor_user']);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Editar Perfil')
            ->assertSee('editor_user');
    }

    public function test_authenticated_user_can_update_profile_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'username' => 'updated_player',
                'email' => 'updated@example.com',
                'data_nascimento' => '1998-04-20',
                'professional_title' => 'QA Tester',
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'updated_player',
            'email' => 'updated@example.com',
            'professional_title' => 'QA Tester',
        ]);
    }

    public function test_authenticated_user_can_update_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'profile_photo' => 'profile_photos/old-avatar.jpg',
        ]);

        Storage::disk('public')->put('profile_photos/old-avatar.jpg', 'old');

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'username' => $user->username,
                'email' => $user->email,
                'data_nascimento' => $user->data_nascimento->format('Y-m-d'),
                'professional_title' => 'Game Designer',
                'profile_photo' => UploadedFile::fake()->image('avatar.png'),
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $user->refresh();

        Storage::disk('public')->assertMissing('profile_photos/old-avatar.jpg');
        Storage::disk('public')->assertExists($user->profile_photo);
        $this->assertStringStartsWith('profile_photos/', $user->profile_photo);
    }

    public function test_authenticated_user_can_add_link_to_portfolio(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('portfolio.upload'), [
                'title' => 'Repositorio Game Hub',
                'description' => 'Projeto integrador da comunidade gamer.',
                'type' => 'link',
                'link_url' => 'https://github.com/LucasJRamos/teste_gamehub',
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('portfolio_items', [
            'user_id' => $user->id,
            'title' => 'Repositorio Game Hub',
            'type' => 'link',
            'link_url' => 'https://github.com/LucasJRamos/teste_gamehub',
        ]);
    }

    public function test_authenticated_user_can_add_image_to_portfolio(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('portfolio.upload'), [
                'title' => 'Screenshot Indie',
                'description' => 'Imagem de gameplay do projeto.',
                'type' => 'image',
                'file' => UploadedFile::fake()->image('screenshot.png'),
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $item = PortfolioItem::where('title', 'Screenshot Indie')->firstOrFail();

        $this->assertSame($user->id, $item->user_id);
        $this->assertSame('image', $item->type);
        Storage::disk('public')->assertExists($item->file_path);
    }

    public function test_authenticated_user_can_delete_own_portfolio_item(): void
    {
        $user = User::factory()->create();

        $item = PortfolioItem::create([
            'user_id' => $user->id,
            'title' => 'Old Project',
            'type' => 'link',
            'link_url' => 'https://github.com/example/old-project',
        ]);

        $this->actingAs($user)
            ->delete(route('portfolio.delete', $item))
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('portfolio_items', [
            'id' => $item->id,
        ]);
    }

    public function test_deleting_image_portfolio_item_removes_file_from_storage(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Storage::disk('public')->put('portfolio/screenshot.png', 'image');

        $item = PortfolioItem::create([
            'user_id' => $user->id,
            'title' => 'Screenshot',
            'type' => 'image',
            'file_path' => 'portfolio/screenshot.png',
        ]);

        $this->actingAs($user)
            ->delete(route('portfolio.delete', $item))
            ->assertRedirect(route('profile'));

        Storage::disk('public')->assertMissing('portfolio/screenshot.png');
        $this->assertDatabaseMissing('portfolio_items', [
            'id' => $item->id,
        ]);
    }

    public function test_user_cannot_delete_another_user_portfolio_item(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $item = PortfolioItem::create([
            'user_id' => $owner->id,
            'title' => 'Protected Project',
            'type' => 'link',
            'link_url' => 'https://github.com/example/protected-project',
        ]);

        $this->actingAs($intruder)
            ->delete(route('portfolio.delete', $item))
            ->assertNotFound();

        $this->assertDatabaseHas('portfolio_items', [
            'id' => $item->id,
        ]);
    }

    public function test_user_can_view_another_public_profile(): void
    {
        $viewer = User::factory()->create();
        $profile = User::factory()->create([
            'username' => 'public_dev',
            'professional_title' => 'Game Programmer',
        ]);

        $this->actingAs($viewer)
            ->get(route('profile.show', $profile))
            ->assertOk()
            ->assertSee('public_dev')
            ->assertSee('Game Programmer');
    }

    public function test_explore_page_filters_users_by_search(): void
    {
        $viewer = User::factory()->create(['username' => 'current_user']);

        User::factory()->create([
            'username' => 'PixelBuilder',
            'professional_title' => 'Game Programmer',
        ]);

        User::factory()->create([
            'username' => 'SoundCrafter',
            'professional_title' => 'Sound Designer',
        ]);

        $this->actingAs($viewer)
            ->get(route('explore', ['search' => 'Pixel']))
            ->assertOk()
            ->assertSee('PixelBuilder')
            ->assertDontSee('SoundCrafter');
    }
}
