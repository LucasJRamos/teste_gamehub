import { Head, router } from '@inertiajs/react';
import { useDeferredValue, useEffect, useState } from 'react';

import TextInput from '../../Components/UI/TextInput';
import UserCard from '../../Components/Users/UserCard';
import AppLayout from '../../Layouts/AppLayout';

export default function Index({ users, filters }) {
    const [search, setSearch] = useState(filters.search ?? '');
    const deferredSearch = useDeferredValue(search);

    useEffect(() => {
        const timeout = setTimeout(() => {
            router.get('/users', deferredSearch ? { search: deferredSearch } : {}, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['users', 'filters'],
            });
        }, 300);

        return () => clearTimeout(timeout);
    }, [deferredSearch]);

    return (
        <AppLayout
            title="Buscar usuarios"
            subtitle="Busca dinamica sem reload completo, filtrando usuarios bloqueados do resultado."
        >
            <Head title="Buscar usuarios" />

            <section className="stack-section">
                <div className="card search-card">
                    <label>
                        <span>Buscar por nome ou titulo</span>
                        <TextInput
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Ex.: programmer, artist, level designer"
                        />
                    </label>
                </div>

                <div className="grid-columns">
                    {users.data.length ? (
                        users.data.map((user) => <UserCard key={user.id} user={user} />)
                    ) : (
                        <div className="card empty-state">
                            Nenhum usuario encontrado para o filtro informado.
                        </div>
                    )}
                </div>

                <div className="card pagination-card">
                    <span>Total encontrado: {users.meta.total}</span>
                    <span>Pagina {users.meta.current_page} de {users.meta.last_page}</span>
                </div>
            </section>
        </AppLayout>
    );
}
