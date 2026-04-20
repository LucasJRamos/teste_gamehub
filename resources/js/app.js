import './bootstrap';
import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { createElement } from 'react';
import { createRoot } from 'react-dom/client';

const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });

createInertiaApp({
    title: (title) => (title ? `${title} | Game Hub` : 'Game Hub'),
    resolve: (name) => pages[`./Pages/${name}.jsx`].default,
    setup({ el, App, props }) {
        createRoot(el).render(createElement(App, props));
    },
    progress: {
        color: '#f97316',
        showSpinner: false,
    },
});
