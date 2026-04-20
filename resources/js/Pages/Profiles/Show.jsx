import { Head, Link } from '@inertiajs/react';

import PortfolioManager from '../../Components/Profiles/PortfolioManager';
import UserActions from '../../Components/Users/UserActions';
import UserCard from '../../Components/Users/UserCard';
import AppLayout from '../../Layouts/AppLayout';

export default function Show({ profile, suggestions }) {
    const user = profile.user;
    const canManage = user.is_own_profile;

    return (
        <AppLayout
            title={canManage ? 'Meu perfil' : `Perfil de ${user.username}`}
            subtitle="Visualizacao de perfil conectada ao backend com portfolio e regras sociais."
            actions={canManage ? <Link href="/profile/edit" className="primary-button">Editar perfil</Link> : null}
        >
            <Head title={canManage ? 'Meu perfil' : user.username} />

            <section className="hero-grid">
                <article className="card profile-hero">
                    <div className="profile-hero-top">
                        <div className="avatar large">
                            {user.profile_photo_url ? (
                                <img src={user.profile_photo_url} alt={user.username} />
                            ) : (
                                <span>{user.username.slice(0, 2).toUpperCase()}</span>
                            )}
                        </div>

                        <div>
                            <p className="eyebrow">Perfil profissional</p>
                            <h2>{user.username}</h2>
                            <p className="muted">{user.professional_title || 'Titulo profissional ainda nao informado.'}</p>
                            <p className="muted">{user.email}</p>
                        </div>
                    </div>

                    <div className="stats-row">
                        <span>{user.followers_count ?? 0} seguidores</span>
                        <span>{user.following_count ?? 0} seguindo</span>
                        <span>{user.portfolio_items_count ?? 0} itens no portfolio</span>
                    </div>

                    {!canManage ? <UserActions user={user} /> : null}
                </article>

                <aside className="stack-section">
                    <div className="section-heading">
                        <div>
                            <h2>Sugestoes relacionadas</h2>
                            <p className="muted">Perfis liberados pela regra de bloqueio.</p>
                        </div>
                    </div>

                    <div className="stack-cards">
                        {suggestions.length ? (
                            suggestions.map((suggestedUser) => <UserCard key={suggestedUser.id} user={suggestedUser} />)
                        ) : (
                            <div className="card empty-state">Sem sugestoes por enquanto.</div>
                        )}
                    </div>
                </aside>
            </section>

            <PortfolioManager items={profile.portfolio_items} canManage={canManage} />
        </AppLayout>
    );
}
