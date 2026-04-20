<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $isOwnProfile = $viewer && $viewer->is($this->resource);

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'data_nascimento' => optional($this->data_nascimento)->format('Y-m-d'),
            'professional_title' => $this->professional_title,
            'profile_photo' => $this->profile_photo,
            'profile_photo_url' => $this->profile_photo
                ? Storage::disk('public')->url($this->profile_photo)
                : null,
            'followers_count' => $this->whenCounted('followers'),
            'following_count' => $this->whenCounted('following'),
            'portfolio_items_count' => $this->whenCounted('portfolioItems'),
            'is_following' => $viewer && ! $isOwnProfile ? $viewer->isFollowing($this->resource) : false,
            'has_blocked' => $viewer && ! $isOwnProfile ? $viewer->hasBlocked($this->resource) : false,
            'is_blocked' => $viewer && ! $isOwnProfile ? $viewer->isBlockedBy($this->resource) : false,
            'is_own_profile' => $isOwnProfile,
            'links' => [
                'profile' => $isOwnProfile ? '/profile' : "/users/{$this->id}",
                'follow' => "/users/{$this->id}/follow",
                'block' => "/users/{$this->id}/block",
            ],
        ];
    }
}
