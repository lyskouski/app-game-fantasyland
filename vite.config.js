import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/craft.css',
                'resources/css/labyrinth.css',
                'resources/css/index.css',
                'resources/js/app.js',
                'resources/js/forum.js',
                'resources/js/labyrinth.js',
                'resources/js/ping.js'
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
