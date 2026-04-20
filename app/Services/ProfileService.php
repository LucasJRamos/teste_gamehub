<?php

namespace App\Services;

use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function loadProfile(User $user): User
    {
        return $user->load([
            'portfolioItems' => fn ($query) => $query->latest(),
        ])->loadCount(['followers', 'following', 'portfolioItems']);
    }

    public function update(User $user, array $data): User
    {
        if (($data['profile_photo'] ?? null) instanceof UploadedFile) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $data['profile_photo'] = $data['profile_photo']->store('profile_photos', 'public');
        } else {
            unset($data['profile_photo']);
        }

        $user->update($data);

        return $this->loadProfile($user->fresh());
    }

    public function addPortfolioItem(User $user, array $data): PortfolioItem
    {
        $payload = [
            'user_id' => $user->getKey(),
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
        ];

        if ($data['type'] === 'image' && ($data['file'] ?? null) instanceof UploadedFile) {
            $payload['file_path'] = $data['file']->store('portfolio', 'public');
        }

        if ($data['type'] === 'link') {
            $payload['link_url'] = $data['link_url'];
        }

        return PortfolioItem::create($payload);
    }

    public function deletePortfolioItem(User $user, PortfolioItem $portfolioItem): void
    {
        abort_unless($portfolioItem->user_id === $user->getKey(), 404);

        if ($portfolioItem->file_path) {
            Storage::disk('public')->delete($portfolioItem->file_path);
        }

        $portfolioItem->delete();
    }
}
