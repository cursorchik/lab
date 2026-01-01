import '../css/app.scss';
import '../css/plugins/fonts.scss';
import '../css/plugins/bootstrap.min.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({ el, App, props }) { const root = createRoot(el); root.render(<App {...props} />); },
    defaults: {
        visitOptions: (href, options) => {
            return { viewTransition: true }
        },
    },
    progress: { color: '#4B5563', showSpinner: true },
});
