import { usePage } from '@inertiajs/react';

export default function FlashMessages() {
    const { flash, errors } = usePage().props;

    return (
        <div className="flash-stack">
            {flash?.success ? <div className="flash success">{flash.success}</div> : null}
            {flash?.error ? <div className="flash error">{flash.error}</div> : null}
            {errors?.user ? <div className="flash error">{errors.user}</div> : null}
        </div>
    );
}
