const mix = require('laravel-mix');
const path = require('path')
// require('dotenv').config();
// const Dotenv = require('dotenv-webpack');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .extract()
    .vue({
        version: 3,
        options: {
            compilerOptions: {
                isCustomElement: (tag) => ['video-player', 'video-js', 'AppLayout', 'button', 'NotificationsButton', 'VideoPlayer', 'Chat', 'ButtonsOttTopRight', 'Info', 'v-select'].includes(tag),
            },
        },
    })
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ])
    .postCss('resources/css/liveStreamGuide.css', 'public/css/liveStreamGuide.css')
    .sourceMaps()
    .alias({
        // if the @ path changes update the webpack.config.js file too!
        '@': path.resolve(__dirname, 'resources/js'),
        '@i': path.resolve('public/images'),
        ziggy: "vendor/tightenco/ziggy/dist/vue",
    })

if (mix.inProduction()) {
    mix.version();
}




