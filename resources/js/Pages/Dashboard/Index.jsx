import { Head, Link } from '@inertiajs/react';

import UserCard from '../../Components/Users/UserCard';
import AppLayout from '../../Layouts/AppLayout';

export default function Dashboard({ currentUser, suggestions }) {
    return (
        <AppLayout
            title="Dashboard"
            subtitle="Resumo da integracao entre autenticacao, perfil e rede social."
            actions={<Link href="/users" className="primary-button">Buscar usuarios</Link>}
        >
            <Head title="Dashboard" />

            <section className="hero-grid">
                <div className="card hero-card">
                    <p className="eyebrow">Conta autenticada</p>
                    <h2>{currentUser.username}</h2>
                    <p className="muted">{currentUser.professional_title || 'Atualize seu perfil para destacar sua especialidade.'}</p>

                    <div className="stats-grid">
                        <div className="stat-card">
                            <strong>{currentUser.followers_count ?? 0}</strong>
                            <span>Seguidores</span>
                        </div>
                        <div className="stat-card">
                            <strong>{currentUser.following_count ?? 0}</strong>
                            <span>Seguindo</span>
                        </div>
                        <div className="stat-card">
                            <strong>{currentUser.portfolio_items_count ?? 0}</strong>
                            <span>Portfolio</span>
                        </div>
                    </div>
                </div>

                <div className="card hero-card accent-card">
                    <p className="eyebrow">Fluxo da Fase 02</p>
                    <h2>Frontend e backend conectados via Inertia.</h2>
                    <p>
                        Login, cadastro, perfil, busca, follow e bloqueio agora usam o backend real,
                        com validacao do Laravel e navegacao SPA.
                    </p>
                    <div className="action-row">
                        <Link href="/profile" className="primary-button">Ver perfil</Link>
                        <Link href="/profile/edit" className="ghost-button">Editar dados</Link>
                    </div>
                </div>
            </section>

            <section className="stack-section">
                <div className="section-heading">
                    <div>
                        <h2>Sugestoes para seguir</h2>
                        <p className="muted">Lista filtrada sem usuarios bloqueados.</p>
                    </div>
                </div>

                <div className="grid-columns">
                    {suggestions.length ? (
                        suggestions.map((user) => <UserCard key={user.id} user={user} />)
                    ) : (
                        <div className="card empty-state">Nenhuma sugestao disponivel agora.</div>
                    )}
                </div>
            </section>
        </AppLayout>
    );
}
