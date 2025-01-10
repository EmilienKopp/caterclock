import { svelte, vitePreprocess } from '@sveltejs/vite-plugin-svelte';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path'; // Add this
import { defineConfig } from 'vite';

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    svelte({
      preprocess: vitePreprocess(),
      onwarn: (warning, handler) => {
        if (warning.code.startsWith('a11y-')) return;
        handler(warning);
      },
    }),
  ],
  resolve: {
    alias: {
      $lib: resolve('./resources/js/Lib'),
      $components: resolve('./resources/js/Components'),
      $vendor: resolve('./vendor'),
      $types: resolve('./resources/js/types'),
      $layouts: resolve('./resources/js/Layouts'),
      $pages: resolve('./resources/js/Pages'),
      $lang: resolve('./lang'),
      $models: resolve('./resources/js/models.d.ts'),
    },
  },
  server: {
    hmr: {
      clientPort: 5173,
    },
  },
});