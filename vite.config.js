import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/craft.css',
                'resources/css/index.css',
                'resources/css/labyrinth.css',
                'resources/js/app.js',
                'resources/js/fight.js',
                'resources/js/forum.js',
                'resources/js/info_runes.js',
                'resources/js/labyrinth.js',
                'resources/js/main_place.js',
                'resources/js/ping.js',
                'resources/js/timer.js',
                'resources/js/swipe.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // The native webview can't reliably fetch separate image files, so inline the small UI images.
        assetsInlineLimit: 16384,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
