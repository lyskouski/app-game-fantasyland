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
                'resources/js/forum.js',
                'resources/js/info_runes.js',
                'resources/js/labyrinth.js',
                'resources/js/main_place.js',
                'resources/js/ping.js',
                'resources/js/timer.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
