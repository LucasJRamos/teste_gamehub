import { Link } from '@inertiajs/react';

import UserActions from './UserActions';

export default function UserCard({ user }) {
    return (
        <article className="card user-card">
            <div className="user-card-header">
                <div className="avatar">
                    {user.profile_photo_url ? (
                        <img src={user.profile_photo_url} alt={user.username} />
                    ) : (
                        <span>{user.username.slice(0, 2).toUpperCase()}</span>
                    )}
                </div>

                <div>
                    <Link href={user.links.profile} className="card-link">
                        {user.username}
                    </Link>
                    <p className="muted">{user.professional_title || 'Perfil em construcao'}</p>
                </div>
            </div>

            <div className="stats-row">
                <span>{user.followers_count ?? 0} seguidores</span>
                <span>{user.following_count ?? 0} seguindo</span>
                <span>{user.portfolio_items_count ?? 0} itens</span>
            </div>

            <UserActions user={user} compact />
        </article>
    );
}
