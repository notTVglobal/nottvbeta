const mix = require('laravel-mix');

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
    // .vue(3)
    .vue({
        version: 3,
        options: {
            compilerOptions: {
                isCustomElement: (tag) => ['video-js'].includes(tag),
            },
        },
    })
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ])
    .alias({
        // if the @ path changes update the webpack.config.js file too!
        '@': 'resources/js',
        ziggy: "vendor/tightenco/ziggy/dist/vue",
    });

if (mix.inProduction()) {
    mix.version();
}

