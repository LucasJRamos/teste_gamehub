<?php

namespace Tests\Unit;

use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_portfolio_items(): void
    {
        $user = User::factory()->create();

        PortfolioItem::create([
            'user_id' => $user->id,
            'title' => 'Game Jam 2026',
            'type' => 'link',
            'link_url' => 'https://github.com/example/game-jam',
        ]);

        $this->assertInstanceOf(HasMany::class, $user->portfolioItems());
        $this->assertCount(1, $user->portfolioItems);
    }

    public function test_portfolio_item_belongs_to_user(): void
    {
        $user = User::factory()->create(['username' => 'pixel_artist']);

        $item = PortfolioItem::create([
            'user_id' => $user->id,
            'title' => 'Pixel Art Portfolio',
            'type' => 'link',
            'link_url' => 'https://github.com/example/pixel-art',
        ]);

        $this->assertInstanceOf(BelongsTo::class, $item->user());
        $this->assertSame('pixel_artist', $item->user->username);
    }
}
