import './bootstrap'

import {createApp, h} from 'vue'
import {createInertiaApp, Head, Link} from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import {InertiaProgress} from '@inertiajs/progress'
import AppLayout from "./Layouts/AppLayout"
import {createPinia} from "pinia"
import {ZiggyVue} from 'ziggy'
import Vue3TouchEvents from "vue3-touch-events"
// import helmet from "helmet";
import "../../resources/css/theme.css" // Magic happens here
// import the fontawesome core
import {library} from '@fortawesome/fontawesome-svg-core'
// import font awesome icon component
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
// import specific icons
import {
    faAngleLeft,
    faArrowRightArrowLeft,
    faCheck,
    faCircle,
    faCircleDown,
    faCircleInfo,
    faClipboard,
    faClipboardList,
    faClapperboard,
    faComments,
    faEye,
    faFilter,
    faFlagUsa,
    faGem,
    faHandsHelping,
    faHeart,
    faLeaf,
    faList,
    faPaperPlane,
    faPencil,
    faPlay,
    faPlayCircle,
    faQuestion,
    faRepeat,
    faRocket,
    faShare,
    faStar,
    faTrashCan,
    faUser,
    faUsers,
    faUserGroup,
    faUserSecret,
    faVolumeMute,
    faVolumeUp,
} from '@fortawesome/free-solid-svg-icons'
// import popper for pop-up tooltips
import Popper from "vue3-popper";
import { format } from 'date-fns'
import VueConfetti from 'vue-confetti'

// import confirm dialog
// import ConfirmDialog from '@/Components/Global/Modals/ConfirmDialog';
// import vueCountryRegionSelect from 'vue3-country-region-select';
// import { setupCalendar } from 'v-calendar';

// Vuetify
// import '@mdi/font/css/materialdesignicons.css'
// import 'vuetify/styles'
// import { createVuetify } from 'vuetify'
// import * as components from 'vuetify/components'
// import * as directives from 'vuetify/directives'
//
// const vuetify = createVuetify({
//     components,
//     directives
// })

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'notTV';
const AudioContext = window.AudioContext || window.webkitAudioContext;
window.audioContext = new AudioContext();

// formatting dates, helper plugins:
// https://jerickson.net/how-to-format-dates-in-vue-3/

const formatDate = () => ({
    methods: {
        formatDate: function (dateString) {
            const date = new Date(dateString);
            // Then specify how you want your dates to be formatted
            return new Intl.DateTimeFormat('default', {dateStyle: 'long'}).format(date);
        },
        formatDateTime: function (dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric', month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit', hour12: true
            };
            return new Intl.DateTimeFormat('default', options).format(date);
        }
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
            .use(VueConfetti)
            // .use(vuetify)
            // .use(vueCountryRegionSelect)
            // .use(setupCalendar, {})
            .component("Link", Link)
            .component("Head", Head)
            .component("font-awesome-icon", FontAwesomeIcon)
            .component("Popper", Popper)
            // .component("ConfirmDialog", ConfirmDialog)
            .mixin(formatDate())
            .mount(el);

        // // Register Inertia navigation event listeners
        // Inertia.on('start', (event) => {
        //     console.log('Navigation started to:', event.detail.visit.url);
        // });
        // Inertia.on('finish', (event) => {
        //     if (event.detail.visit.completed) console.log('Navigation completed to:', event.detail.visit.url);
        //     else if (event.detail.visit.interrupted) console.log('Navigation interrupted from:', event.detail.visit.url);
        //     else if (event.detail.visit.cancelled) console.log('Navigation cancelled to:', event.detail.visit.url);
        // });

    },
});

library.add(faArrowRightArrowLeft, faUserSecret, faPlay, faPlayCircle, faQuestion, faStar, faUsers, faUserGroup, faHandsHelping, faRocket,
            faEye, faComments, faPaperPlane, faUser, faGem, faHeart, faLeaf, faFlagUsa, faList, faFilter,
            faShare, faCircleDown, faRepeat, faCheck, faAngleLeft, faTrashCan, faCircleInfo, faPencil, faClipboard, faClipboardList, faCircle, faClapperboard, faVolumeMute, faVolumeUp);

InertiaProgress.init({ delay: 250, color: '#FCEF5B', includeCSS: true, showSpinner: true, });

// let lastUrl = window.location.pathname;
//
// Inertia.on('navigate', (event) => {
//     lastUrl = event.detail.page.url;
// });