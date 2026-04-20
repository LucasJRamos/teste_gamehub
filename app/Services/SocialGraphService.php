<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class SocialGraphService
{
    public function ensureVisible(User $viewer, User $target): void
    {
        abort_if($viewer->isBlockedWith($target), 404);
    }

    public function follow(User $viewer, User $target): void
    {
        $this->guardSelfAction($viewer, $target, 'Nao e possivel seguir o proprio perfil.');
        $this->ensureVisible($viewer, $target);

        $viewer->following()->syncWithoutDetaching([$target->getKey()]);
    }

    public function unfollow(User $viewer, User $target): void
    {
        $this->guardSelfAction($viewer, $target, 'Nao e possivel deixar de seguir o proprio perfil.');

        $viewer->following()->detach($target->getKey());
    }

    public function block(User $viewer, User $target): void
    {
        $this->guardSelfAction($viewer, $target, 'Nao e possivel bloquear o proprio perfil.');

        $viewer->blockedUsers()->syncWithoutDetaching([$target->getKey()]);
        $viewer->following()->detach($target->getKey());
        $target->following()->detach($viewer->getKey());
    }

    public function unblock(User $viewer, User $target): void
    {
        $this->guardSelfAction($viewer, $target, 'Nao e possivel desbloquear o proprio perfil.');

        $viewer->blockedUsers()->detach($target->getKey());
    }

    protected function guardSelfAction(User $viewer, User $target, string $message): void
    {
        if ($viewer->is($target)) {
            throw ValidationException::withMessages([
                'user' => $message,
            ]);
        }
    }
}
