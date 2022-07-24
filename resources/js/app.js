// import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from "./Layouts/AppLayout";
// import NoLayout from "./Layouts/NoLayout";
import { ZiggyVue } from 'ziggy';
import VideoPlayer from "./Components/VideoPlayer";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'notTV';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async name => {
        let page = await import(`./Pages/${name}`);

        page = page.default;

        if (! page.layout) {
            page.layout = AppLayout;
        }
        return page;
    },
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component("Link", Link)
            .component("Head", Head)
            .component('VideoPlayer', VideoPlayer)
            .mount(el);
    },
});

InertiaProgress.init({ delay: 250, color: '#FCEF5B', includeCSS: true, showSpinner: true, });
