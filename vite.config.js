import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ command, mode }) => {
    const isProduction = mode === 'production';
    
    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            host: '127.0.0.1',
            port: 5173,
            strictPort: true,
        },
        build: {
            minify: 'esbuild', // Default, but explicit for clarity
            sourcemap: isProduction ? false : 'inline',
            rollupOptions: {
                output: {
                    chunkFileNames: 'assets/js/[name]-[hash].js',
                    entryFileNames: 'assets/js/[name]-[hash].js',
                    assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
                }
            },
        },
    };
});
