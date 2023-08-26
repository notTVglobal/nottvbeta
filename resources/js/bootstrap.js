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
    // broadcaster: 'pusher',
    // key: process.env.MIX_PUSHER_APP_KEY,
    // forceTLS: true,
    // disableStats: true,
    // wsHost: 'socket.not.tv',
    // wsPort: 443,
    // wssPort: 443,
    // encrypted: true,
    // enabledTransports: ['ws', 'wss'],

    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: 'nottv.local',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});
