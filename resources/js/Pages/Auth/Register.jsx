import { Head, Link, useForm } from '@inertiajs/react';

import FieldError from '../../Components/UI/FieldError';
import TextInput from '../../Components/UI/TextInput';

export default function Register() {
    const form = useForm({
        username: '',
        email: '',
        data_nascimento: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (event) => {
        event.preventDefault();
        form.post('/register');
    };

    return (
        <>
            <Head title="Cadastro" />

            <div className="auth-shell">
                <section className="auth-panel auth-panel-accent">
                    <p className="eyebrow">Cadastro</p>
                    <h1>Entre no ecossistema Game Hub com o backend ja integrado.</h1>
                    <p>
                        O cadastro autentica por sessao, valida dados no Laravel e redireciona
                        automaticamente para o dashboard.
                    </p>
                </section>

                <section className="auth-panel">
                    <div className="section-heading">
                        <div>
                            <h2>Criar conta</h2>
                            <p className="muted">Preencha os dados para iniciar a sessao imediatamente.</p>
                        </div>
                    </div>

                    <form className="stack-form" onSubmit={submit}>
                        <label>
                            <span>Usuario</span>
                            <TextInput
                                value={form.data.username}
                                onChange={(event) => form.setData('username', event.target.value)}
                            />
                            <FieldError message={form.errors.username} />
                        </label>

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
                            <span>Data de nascimento</span>
                            <TextInput
                                type="date"
                                value={form.data.data_nascimento}
                                onChange={(event) => form.setData('data_nascimento', event.target.value)}
                            />
                            <FieldError message={form.errors.data_nascimento} />
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

                        <label>
                            <span>Confirmar senha</span>
                            <TextInput
                                type="password"
                                value={form.data.password_confirmation}
                                onChange={(event) => form.setData('password_confirmation', event.target.value)}
                            />
                        </label>

                        <button type="submit" className="primary-button" disabled={form.processing}>
                            Criar conta
                        </button>
                    </form>

                    <p className="muted">
                        Ja possui conta? <Link href="/login" className="card-link">Voltar para login</Link>
                    </p>
                </section>
            </div>
        </>
    );
}
