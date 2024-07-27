// resources/js/app.js
import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import i18n from './i18n';
import axios from 'axios';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue);

        axios.get(`/lang/${i18n.global.locale.value}`)
            .then(response => {
                i18n.global.setLocaleMessage(response.data.locale, response.data.messages);
                app.mount(el);
            })
            .catch(error => {
                console.error('Error loading translations:', error);
                app.mount(el);
            });

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
