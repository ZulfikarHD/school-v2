import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { visualizer } from 'rollup-plugin-visualizer';
import { defineConfig } from 'vite';

export default defineConfig(({ command }) => ({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        // Wayfinder only in serve mode — build uses pre-generated
        // routes via `php artisan wayfinder:generate` because the
        // vite build container (node:22-alpine) has no PHP runtime.
        command === 'serve'
            ? wayfinder({ formVariants: true })
            : null,
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        // Bundle size monitoring — target < 200KB initial JS (gzipped).
        // Generates stats.html after `yarn build` for visual inspection.
        visualizer({
            filename: 'storage/app/bundle-stats.html',
            gzipSize: true,
            brotliSize: true,
            template: 'treemap',
        }),
    ].filter(Boolean),
    // Docker HMR: bind to all interfaces so the vite container
    // is reachable from the host browser. Polling required for
    // reliable file change detection inside Docker bind mounts.
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            interval: 1000,
        },
    },
}));
