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

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    // wsHost: process.env.MIX_WEBSOCKET_HOST,
    // wsPort: process.env.MIX_WEBSOCKET_PORT,
    // wssPort: process.env.MIX_WEBSOCKET_PORT_SSL,


    // wsHost: 'socket.not.tv',
    // wsPort: 443,
    // wssPort: 443,
    // disableStats: true,
    // forceTLS: true,
    // encrypted: true,
    // enabledTransports: ['ws', 'wss'],

    wsHost: 'localhost',
    wsPort: 6001,
    disableStats: true,
    forceTLS: false,
    encrypted: false,
    // enabledTransports: ['ws'],

    // broadcaster: 'pusher',
    // key: process.env.MIX_PUSHER_APP_KEY,

    // forceTLS: process.env.MIX_WEBSOCKET_FORCE_TLS,
    // disableStats: process.env.MIX_WEBSOCKET_DISABLE_STATS,
    // encrypted: process.env.MIX_WEBSOCKET_ENCRYPTED,
    // enabledTransports: process.env.MIX_WEBSOCKET_ENABLED_TRANSPORTS,
});
