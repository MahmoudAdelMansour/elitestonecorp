import '../css/app.css';

import { createInertiaApp, type ResolvedComponent } from '@inertiajs/react';
import Layout from '@/components/Layout';

document.documentElement.classList.add('js');

const pages = import.meta.glob<{ default: ResolvedComponent }>('./pages/**/*.tsx');

createInertiaApp({
    title: (title) => title,
    resolve: async (name) => (await pages[`./pages/${name}.tsx`]()).default,
    layout: () => Layout,
    progress: { color: '#cfc7c0', showSpinner: false },
});
