import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite'
import Components from "unplugin-vue-components/vite";



export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
        Components({
            dirs: ['resources/js/components'],
            directoryAsNamespace: false,
            resolve: (name) => {
                const pascal = snakeToPascal(name.replace(/-/g, '_'))

                return {
                    name: pascal,
                    from: `resources/js/components/${pascal}.vue`,
                }
            },

            dts: 'resources/js/components.d.ts',
        }),

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

function snakeToPascal(name) {
    return name
        .split('_')
        .map(p => p.charAt(0).toUpperCase() + p.slice(1))
        .join('')
}
