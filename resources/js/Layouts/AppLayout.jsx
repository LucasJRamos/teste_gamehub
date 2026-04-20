import { Link, router, usePage } from '@inertiajs/react';

import FlashMessages from '../Components/UI/FlashMessages';

const navItems = [
    { href: '/dashboard', label: 'Dashboard' },
    { href: '/profile', label: 'Perfil' },
    { href: '/users', label: 'Buscar pessoas' },
];

export default function AppLayout({ title, subtitle, actions, children }) {
    const { url, props } = usePage();
    const authUser = props.auth?.user;

    const logout = () => {
        router.post('/logout');
    };

    return (
        <div className="shell">
            <aside className="sidebar">
                <div className="brand">
                    <div className="brand-mark">GH</div>
                    <div>
                        <strong>Game Hub</strong>
                        <p>Rede criativa para devs indie</p>
                    </div>
                </div>

                <nav className="nav">
                    {navItems.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={`nav-link ${url.startsWith(item.href) ? 'is-active' : ''}`}
                        >
                            {item.label}
                        </Link>
                    ))}
                </nav>

                <div className="sidebar-card">
                    <p className="eyebrow">Sessao ativa</p>
                    <h3>{authUser?.username}</h3>
                    <p>{authUser?.professional_title || 'Complete seu perfil para aparecer melhor nas buscas.'}</p>
                    <button type="button" className="ghost-button" onClick={logout}>
                        Sair
                    </button>
                </div>
            </aside>

            <main className="content">
                <header className="page-header">
                    <div>
                        <p className="eyebrow">Fase 02</p>
                        <h1>{title}</h1>
                        {subtitle ? <p className="muted">{subtitle}</p> : null}
                    </div>
                    {actions ? <div className="header-actions">{actions}</div> : null}
                </header>

                <FlashMessages />

                {children}
            </main>
        </div>
    );
}
