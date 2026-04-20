<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserDirectoryService
{
    public function search(User $viewer, ?string $search = null): LengthAwarePaginator
    {
        return User::query()
            ->visibleTo($viewer)
            ->when($search, function ($query, $term) {
                $query->where(function ($nestedQuery) use ($term) {
                    $nestedQuery
                        ->where('username', 'like', "%{$term}%")
                        ->orWhere('professional_title', 'like', "%{$term}%");
                });
            })
            ->withCount(['followers', 'following', 'portfolioItems'])
            ->orderBy('username')
            ->paginate(9)
            ->withQueryString();
    }

    public function suggestions(User $viewer, int $limit = 4): Collection
    {
        return User::query()
            ->visibleTo($viewer)
            ->whereNotIn('users.id', $viewer->following()->select('followed_id'))
            ->withCount(['followers', 'following', 'portfolioItems'])
            ->orderByDesc('followers_count')
            ->limit($limit)
            ->get();
    }
}
