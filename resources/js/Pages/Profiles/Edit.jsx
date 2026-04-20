import { Head, useForm } from '@inertiajs/react';

import FieldError from '../../Components/UI/FieldError';
import TextInput from '../../Components/UI/TextInput';
import AppLayout from '../../Layouts/AppLayout';

export default function Edit({ profile }) {
    const form = useForm({
        username: profile.username ?? '',
        email: profile.email ?? '',
        data_nascimento: profile.data_nascimento ?? '',
        professional_title: profile.professional_title ?? '',
        profile_photo: null,
    });

    const submit = (event) => {
        event.preventDefault();
        form.post('/profile', {
            method: 'put',
            forceFormData: true,
        });
    };

    return (
        <AppLayout title="Editar perfil" subtitle="Dados enviados ao Laravel via Inertia com PUT e upload protegido por CSRF.">
            <Head title="Editar perfil" />

            <form className="card form-grid" onSubmit={submit}>
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
                    <span>Titulo profissional</span>
                    <TextInput
                        value={form.data.professional_title}
                        onChange={(event) => form.setData('professional_title', event.target.value)}
                        placeholder="Ex.: Gameplay Programmer"
                    />
                    <FieldError message={form.errors.professional_title} />
                </label>

                <label className="full-width">
                    <span>Foto de perfil</span>
                    <input
                        type="file"
                        className="text-input file-input"
                        accept="image/*"
                        onChange={(event) => form.setData('profile_photo', event.target.files[0])}
                    />
                    <FieldError message={form.errors.profile_photo} />
                </label>

                <button type="submit" className="primary-button" disabled={form.processing}>
                    Salvar alteracoes
                </button>
            </form>
        </AppLayout>
    );
}
