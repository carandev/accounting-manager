import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    base: '/',

    // Fuerza que los assets se resuelvan desde tu dominio en https
    server: {
        https: true,
        host: '0.0.0.0',
    },
});
