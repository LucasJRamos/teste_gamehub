import { Head, Link, useForm } from '@inertiajs/react';

import FieldError from '../../Components/UI/FieldError';
import TextInput from '../../Components/UI/TextInput';

export default function Login() {
    const form = useForm({
        email: '',
        password: '',
    });

    const submit = (event) => {
        event.preventDefault();
        form.post('/login');
    };

    return (
        <>
            <Head title="Login" />

            <div className="auth-shell">
                <section className="auth-panel auth-panel-accent">
                    <p className="eyebrow">Game Hub</p>
                    <h1>Integre seu perfil, portfolio e rede em um unico fluxo.</h1>
                    <p>
                        Esta fase conecta frontend React + Inertia ao backend Laravel com sessao,
                        validacao, busca e interacoes sociais reais.
                    </p>
                </section>

                <section className="auth-panel">
                    <div className="section-heading">
                        <div>
                            <h2>Entrar</h2>
                            <p className="muted">Acesse sua conta para ir direto ao dashboard.</p>
                        </div>
                    </div>

                    <form className="stack-form" onSubmit={submit}>
                        <label>
                            <span>E-mail</span>
                            <TextInput
                                type="email"
                                value={form.data.email}
                                onChange={(event) => form.setData('email', event.target.value)}
                            />
                            <FieldError message={form.errors.email} />
                        </label>

                        <label>
                            <span>Senha</span>
                            <TextInput
                                type="password"
                                value={form.data.password}
                                onChange={(event) => form.setData('password', event.target.value)}
                            />
                            <FieldError message={form.errors.password} />
                        </label>

                        <button type="submit" className="primary-button" disabled={form.processing}>
                            Entrar
                        </button>
                    </form>

                    <p className="muted">
                        Ainda nao tem conta? <Link href="/register" className="card-link">Criar cadastro</Link>
                    </p>
                </section>
            </div>
        </>
    );
}
