import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Pastikan ini ada
                'resources/js/app.js',  // Pastikan ini ada
            ],
            refresh: true,
        }),
    ],
});