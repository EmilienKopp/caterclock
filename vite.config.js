import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import sveltePreprocess from 'svelte-preprocess';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        svelte({
            preprocess: sveltePreprocess({
                typescript: true,
            }),
        }),
    ],
    resolve: {
        alias: {
            '$lib': '/resources/js/Lib',
            '$components': '/resources/js/Components',
            '$vendor': '/vendor',
            '$types': '/resources/js/types.ts',
            '$layouts': '/resources/js/Layouts',
            '$pages': '/resources/js/Pages',
            '$lang': '/lang',
            '$models': '/resources/js/models.d.ts',
        },
    },
});
