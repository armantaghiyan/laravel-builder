import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite'



export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '~': path.resolve(__dirname, './resources'),
        },
    },
    server: {
        https: false,
        host: 'localhost',
        port: 5174,
        strictPort: true,
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    let ext = assetInfo.name.split('.').at(1);

                    if(ext === 'css'){
                        return `assets/[name]-[hash][extname]`;
                    }

                    if(ext === 'eot' || ext === 'ttf' || ext === 'woff' || ext === 'woff2'){
                        return `fonts/[name][extname]`;
                    }

                    if(ext === 'png' || ext === 'svg'){
                        return `images/[name][extname]`;
                    }

                    return `${ext}/[name][extname]`;
                },
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
            },
        },
    },
});
