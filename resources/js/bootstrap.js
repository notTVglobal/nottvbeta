import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// const echoKey = process.env.MIX_REVERB_APP_KEY;
// const echoHost = process.env.MIX_REVERB_HOST;
// const echoPort = process.env.MIX_REVERB_PORT;

// console.log('Echo Key:', echoKey); // Debug log to verify key
// console.log('Echo Host:', echoHost); // Debug log to verify host

window.Echo = new Echo({
    // broadcaster: 'pusher',
    // key: process.env.MIX_PUSHER_APP_KEY,
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    // forceTLS: process.env.MIX_PUSHER_APP_TLS,
    // encrypted: process.env.MIX_PUSHER_APP_ENCRYPTED,
    // // broadcaster: 'reverb',
    // key: process.env.MIX_PUSHER_APP_KEY,


    // wsHost: process.env.MIX_WEBSOCKET_HOST,
    // wsPort: process.env.MIX_WEBSOCKET_PORT,
    // wssPort: process.env.MIX_WEBSOCKET_PORT_SSL,

    //




    // wsHost: process.env.MIX_REVERB_HOST,
    // wsPort: process.env.MIX_REVERB_HOST,
    // wssPort: process.env.MIX_REVERB_PORT,
    //
    // enabledTransports: ['ws', 'wss'],

    broadcaster: 'reverb',
    key: process.env.MIX_REVERB_APP_KEY,
    wsHost: process.env.MIX_REVERB_HOST,
    wsPort: process.env.MIX_REVERB_PORT ?? 80,
    wssPort: process.env.MIX_REVERB_PORT ?? 443,
    forceTLS: (process.env.MIX_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],

    // broadcaster: 'reverb',
    // key: import.meta.env.VITE_REVERB_APP_KEY,
    // wsHost: import.meta.env.VITE_REVERB_HOST,
    // wsPort: import.meta.env.VITE_REVERB_PORT,
    // wssPort: import.meta.env.VITE_REVERB_PORT,
    // forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    // enabledTransports: ['ws', 'wss'],

    //
    // broadcaster: 'reverb',
    // key: '1q9jyzxobvb6v9zevixb',
    // wsHost: 'localhost',
    // wsPort: '8080',
    // wssPort: '8080',
    // forceTLS: false,
    // enabledTransports: ['ws', 'wss'],

    //
    // wsHost: 'socket.not.tv',
    // wsPort: 443,
    // wssPort: 443,
    // disableStats: true,
    // forceTLS: true,
    // encrypted: true,
    // enabledTransports: ['ws', 'wss'],

    // wsHost: 'localhost',
    // wsPort: 6001,
    // disableStats: true,
    // forceTLS: false,
    // encrypted: false,
    // enabledTransports: ['ws'],

    // broadcaster: 'pusher',
    // key: process.env.MIX_PUSHER_APP_KEY,

    // forceTLS: process.env.MIX_WEBSOCKET_FORCE_TLS,
    // disableStats: process.env.MIX_WEBSOCKET_DISABLE_STATS,
    // encrypted: process.env.MIX_WEBSOCKET_ENCRYPTED,
    // enabledTransports: process.env.MIX_WEBSOCKET_ENABLED_TRANSPORTS,
});

console.log('Echo initialized:', window.Echo);
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */


