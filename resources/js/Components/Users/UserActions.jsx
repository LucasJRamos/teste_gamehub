import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function UserActions({ user, compact = false }) {
    const [processing, setProcessing] = useState(false);

    const follow = () => {
        setProcessing(true);
        router.post(user.links.follow, {}, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const unfollow = () => {
        setProcessing(true);
        router.delete(user.links.follow, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const block = () => {
        setProcessing(true);
        router.post(user.links.block, {}, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const unblock = () => {
        setProcessing(true);
        router.delete(user.links.block, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    if (user.is_own_profile) {
        return null;
    }

    return (
        <div className={`action-row ${compact ? 'compact' : ''}`}>
            <button
                type="button"
                className="primary-button"
                disabled={processing}
                onClick={user.is_following ? unfollow : follow}
            >
                {user.is_following ? 'Deixar de seguir' : 'Seguir'}
            </button>

            <button
                type="button"
                className="ghost-button"
                disabled={processing}
                onClick={user.has_blocked ? unblock : block}
            >
                {user.has_blocked ? 'Desbloquear' : 'Bloquear'}
            </button>
        </div>
    );
}
