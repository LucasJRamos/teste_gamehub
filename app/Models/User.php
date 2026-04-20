<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'data_nascimento',
        'password',
        'profile_photo',
        'professional_title',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'data_nascimento' => 'date',
        ];
    }

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class);
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'followed_id')
            ->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'followed_id', 'follower_id')
            ->withTimestamps();
    }

    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'blocks', 'blocker_id', 'blocked_id')
            ->withTimestamps();
    }

    public function blockedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'blocks', 'blocked_id', 'blocker_id')
            ->withTimestamps();
    }

    public function isFollowing(self $user): bool
    {
        return $this->following()->whereKey($user->getKey())->exists();
    }

    public function hasBlocked(self $user): bool
    {
        return $this->blockedUsers()->whereKey($user->getKey())->exists();
    }

    public function isBlockedBy(self $user): bool
    {
        return $this->blockedByUsers()->whereKey($user->getKey())->exists();
    }

    public function isBlockedWith(self $user): bool
    {
        return $this->hasBlocked($user) || $this->isBlockedBy($user);
    }

    public function scopeVisibleTo(Builder $query, self $viewer): Builder
    {
        return $query
            ->whereKeyNot($viewer->getKey())
            ->whereNotIn('users.id', $viewer->blockedUsers()->select('blocked_id'))
            ->whereNotIn('users.id', $viewer->blockedByUsers()->select('blocker_id'));
    }
}
