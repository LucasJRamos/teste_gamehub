import { router, useForm } from '@inertiajs/react';

import FieldError from '../UI/FieldError';
import TextInput from '../UI/TextInput';

export default function PortfolioManager({ items, canManage }) {
    const form = useForm({
        title: '',
        description: '',
        type: 'link',
        link_url: '',
        file: null,
    });

    const submit = (event) => {
        event.preventDefault();
        form.post('/portfolio/upload', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    };

    const removeItem = (id) => {
        router.delete(`/portfolio/${id}`, {
            preserveScroll: true,
        });
    };

    return (
        <section className="stack-section">
            <div className="section-heading">
                <div>
                    <h2>Portfolio</h2>
                    <p className="muted">Links e artes publicados no backend real.</p>
                </div>
            </div>

            {canManage ? (
                <form className="card form-grid" onSubmit={submit}>
                    <label>
                        <span>Titulo</span>
                        <TextInput
                            value={form.data.title}
                            onChange={(event) => form.setData('title', event.target.value)}
                            placeholder="Ex.: Demo no itch.io"
                        />
                        <FieldError message={form.errors.title} />
                    </label>

                    <label>
                        <span>Tipo</span>
                        <select
                            className="text-input"
                            value={form.data.type}
                            onChange={(event) => form.setData('type', event.target.value)}
                        >
                            <option value="link">Link</option>
                            <option value="image">Imagem</option>
                        </select>
                    </label>

                    <label className="full-width">
                        <span>Descricao</span>
                        <textarea
                            className="text-area"
                            value={form.data.description}
                            onChange={(event) => form.setData('description', event.target.value)}
                            placeholder="Contexto rapido do projeto"
                        />
                        <FieldError message={form.errors.description} />
                    </label>

                    {form.data.type === 'link' ? (
                        <label className="full-width">
                            <span>URL</span>
                            <TextInput
                                value={form.data.link_url}
                                onChange={(event) => form.setData('link_url', event.target.value)}
                                placeholder="https://github.com/..."
                            />
                            <FieldError message={form.errors.link_url} />
                        </label>
                    ) : (
                        <label className="full-width">
                            <span>Arquivo</span>
                            <input
                                type="file"
                                className="text-input file-input"
                                accept="image/*"
                                onChange={(event) => form.setData('file', event.target.files[0])}
                            />
                            <FieldError message={form.errors.file} />
                        </label>
                    )}

                    <button type="submit" className="primary-button" disabled={form.processing}>
                        Adicionar ao portfolio
                    </button>
                </form>
            ) : null}

            <div className="portfolio-grid">
                {items.length ? (
                    items.map((item) => (
                        <article key={item.id} className="card portfolio-card">
                            <div className="section-heading">
                                <div>
                                    <h3>{item.title || 'Item sem titulo'}</h3>
                                    <p className="muted">{item.description || 'Sem descricao adicional.'}</p>
                                </div>
                                {canManage ? (
                                    <button
                                        type="button"
                                        className="ghost-button"
                                        onClick={() => removeItem(item.id)}
                                    >
                                        Remover
                                    </button>
                                ) : null}
                            </div>

                            {item.file_url ? <img src={item.file_url} alt={item.title || 'Portfolio'} className="portfolio-image" /> : null}
                            {item.link_url ? (
                                <a href={item.link_url} target="_blank" rel="noreferrer" className="card-link">
                                    Abrir link
                                </a>
                            ) : null}
                        </article>
                    ))
                ) : (
                    <div className="card empty-state">
                        Nenhum item publicado ainda.
                    </div>
                )}
            </div>
        </section>
    );
}
