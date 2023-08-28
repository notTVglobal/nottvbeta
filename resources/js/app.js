import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from "./Layouts/AppLayout";
import { createPinia } from "pinia";
import { ZiggyVue } from 'ziggy';
import Vue3TouchEvents from "vue3-touch-events";
// import helmet from "helmet";

import "../../resources/css/theme.css"; // Magic happens here
// import the fontawesome core
import { library } from '@fortawesome/fontawesome-svg-core'
// import font awesome icon component
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// import specific icons
import { faUserSecret, faPlay, faQuestion, faStar, faUsers, faHandsHelping,
        faRocket, faEye, faComments, faPaperPlane, faUser, faLeaf, faFlagUsa,
        faList, faFilter, faShare, faCircleDown, faRepeat, faCheck } from '@fortawesome/free-solid-svg-icons'
// import popper for pop-up tooltips
import Popper from "vue3-popper";
// import confirm dialog
import ConfirmDialog from '@/Components/Modals/ConfirmDialog';
import vueCountryRegionSelect from 'vue3-country-region-select';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'notTV';


// formatting dates, helper plugins:
// https://jerickson.net/how-to-format-dates-in-vue-3/

const formatDate = () => ({
    methods: {
        formatDate: function (dateString) {
            const date = new Date(dateString);
            // Then specify how you want your dates to be formatted
            return new Intl.DateTimeFormat('default', {dateStyle: 'long'}).format(date);
        },
    },
})

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
            // .use(VueReCaptcha, { siteKey: captchaKey } )
            .use(ZiggyVue, Ziggy)
            .use(createPinia())
            .use(Vue3TouchEvents)
            .use(vueCountryRegionSelect)
            .component("Link", Link)
            .component("Head", Head)
            .component("font-awesome-icon", FontAwesomeIcon)
            .component("Popper", Popper)
            .component("ConfirmDialog", ConfirmDialog)
            .mixin(formatDate())
            .mount(el);
    },
});

library.add(faUserSecret, faPlay, faQuestion, faStar, faUsers, faHandsHelping, faRocket,
            faEye, faComments, faPaperPlane, faUser, faLeaf, faFlagUsa, faList, faFilter,
            faShare, faCircleDown, faRepeat, faCheck);

InertiaProgress.init({ delay: 250, color: '#FCEF5B', includeCSS: true, showSpinner: true, });

