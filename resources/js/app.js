// resources/js/app.js
import './bootstrap';
import '@placetopay/spartan-vue/style.css';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import i18n from './i18n';
import axios from 'axios';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const options = {
    position: 'top-right',
    timeout: 5000,
    closeOnClick: true,
    pauseOnHover: true,
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue)
            .use(Toast, options);

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
