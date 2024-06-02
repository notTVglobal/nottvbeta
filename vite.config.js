import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';
import vue from '@vitejs/plugin-vue';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/manifest.js',
                'resources/js/vendor.js',
                ],
            ssr: 'resources/js/ssr.js',
        }),
        // react(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, '/resources/js'),
            '@i': path.resolve('/public/images'),
            ziggy: "/vendor/tightenco/ziggy/dist/vue",
        }
    }
});