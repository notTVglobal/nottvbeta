import './bootstrap'

import {createApp, h} from 'vue'
import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import AppLayout from "./Layouts/AppLayout"
import {createPinia} from "pinia"
import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from "./ziggy.js"
import Vue3TouchEvents from "vue3-touch-events"
// import helmet from "helmet";
import "../../resources/css/theme.css" // Magic happens here
// import the fontawesome core
import { library } from '@fortawesome/fontawesome-svg-core'
// import font awesome icon component
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// import specific icons
import {
    fa1,
    fa2,
    fa3,
    fa4,
    fa5,
    fa6,
    fa7,
    fa8,
    fa9,
    faAngleLeft,
    faArrowRightArrowLeft,
    faBookOpen,
    faCheck,
    faCircle,
    faCircleDown,
    faCircleExclamation,
    faCircleInfo,
    faClipboard,
    faClipboardList,
    faClapperboard,
    faComments,
    faCommentDots,
    faCopy,
    faEnvelope,
    faEye,
    faFilter,
    faFlagUsa,
    faGem,
    faHandsHelping,
    faHeart,
    faLeaf,
    faList,
    faLock,
    faPaperPlane,
    faPencil,
    faPlay,
    faPlayCircle,
    faQuestion,
    faRepeat,
    faRocket,
    faRss,
    faShare,
    faStar,
    faTrashCan,
    faUser,
    faUsers,
    faUserGroup,
    faUserSecret,
    faVideo,
    faVolumeMute,
    faVolumeUp,
    faWrench,
} from '@fortawesome/free-solid-svg-icons'
import {  } from '@fortawesome/free-regular-svg-icons';
import {
    faBuffer, faEvernote, faFacebookF, faFlipboard, faHackerNews,
    faLinkedin, faFacebookMessenger, faPinterest, faGetPocket, faQuora,
    faRedditAlien, faSkype, faStumbleupon, faTelegramPlane, faTumblr, faTwitter, faViber,
    faVk, faWhatsapp, faWordpress, faYammer
} from '@fortawesome/free-brands-svg-icons';
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
    progress: {
        delay: 250, color: '#FCEF5B', includeCSS: true, showSpinner: true,
    },
    title: (title) => `${title} - ${appName}`,
    resolve: async name => {

        let page = await import(`./Pages/${name}`);

        page = page.default;

        if (! page.layout) {
            page.layout = AppLayout;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        const VueApp = createApp({ render: () => h(App, props) })

            VueApp.config.globalProperties.$route = route

            VueApp.use(plugin)
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
        // router.on('start', (event) => {
        //     console.log('Navigation started to:', event.detail.visit.url);
        // });
        // router.on('finish', (event) => {
        //     if (event.detail.visit.completed) console.log('Navigation completed to:', event.detail.visit.url);
        //     else if (event.detail.visit.interrupted) console.log('Navigation interrupted from:', event.detail.visit.url);
        //     else if (event.detail.visit.cancelled) console.log('Navigation cancelled to:', event.detail.visit.url);
        // });

    },
});

// Add icons to the library
library.add(
    fa1, fa2, fa3, fa4, fa5, fa6, fa7, fa8, fa9,
    faAngleLeft, faArrowRightArrowLeft, faBookOpen, faCheck, faCircle, faCircleDown, faCopy, faCircleExclamation, faCircleInfo, faClipboard, faClipboardList, faClapperboard, faComments, faEye, faFilter, faFlagUsa, faGem, faHandsHelping, faHeart, faLeaf, faList, faLock, faPaperPlane, faPencil, faPlay, faPlayCircle, faQuestion, faRepeat, faRocket, faRss, faShare, faStar, faTrashCan, faUser, faUsers, faUserGroup, faUserSecret, faVideo, faVolumeMute, faVolumeUp, faWrench,
    faBuffer, faEnvelope, faEvernote, faFacebookF, faFlipboard, faHackerNews, faLinkedin, faFacebookMessenger, faPinterest, faGetPocket, faQuora, faRedditAlien, faSkype, faStumbleupon, faCommentDots, faTelegramPlane, faTumblr, faTwitter, faViber, faVk, faWhatsapp, faWordpress, faYammer,
    faEnvelope, faCommentDots
);

// let lastUrl = window.location.pathname;
//
// router.on('navigate', (event) => {
//     lastUrl = event.detail.page.url;
// });
