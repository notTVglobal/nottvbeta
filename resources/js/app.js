import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from "./Layouts/AppLayout";
import { createPinia } from "pinia";
import { ZiggyVue } from 'ziggy';

import "../../resources/css/theme.css"; // Magic happens here
// import the fontawesome core
import { library } from '@fortawesome/fontawesome-svg-core'
// import font awesome icon component
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// import specific icons
import { faUserSecret, faPlay, faQuestion, faStar, faUsers, faHandsHelping, faRocket, faEye, faComments, faPaperPlane } from '@fortawesome/free-solid-svg-icons'
// import popper for pop-up tooltips
import Popper from "vue3-popper";
// import confirm dialog
import ConfirmDialog from '@/Components/Modals/ConfirmDialog';

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
            .use(createPinia())
            .component("Link", Link)
            .component("Head", Head)
            .component("font-awesome-icon", FontAwesomeIcon)
            .component("Popper", Popper)
            .component("ConfirmDialog", ConfirmDialog)
            .mount(el);

    },
});



library.add(faUserSecret, faPlay, faQuestion, faStar, faUsers, faHandsHelping, faRocket, faEye, faComments, faPaperPlane);

InertiaProgress.init({ delay: 250, color: '#FCEF5B', includeCSS: true, showSpinner: true, });


