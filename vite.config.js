import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    server: {
        port: 8880,
        strictPort: true,
        hmr: {
            port: 8880,
            host: 'localhost',
            protocol: 'ws',
        }
    },
    build: {
        manifest: true,
        outDir: 'assets',
        assetsDir: 'assetsDIR',
        // publicDir: 'public',
        emptyOutDir: true, // delete the contents of the output directory before each build

        // https://rollupjs.org/guide/en/#big-list-of-options
        rollupOptions: {
            input: [
                'resources/admin/main.js',
                // 'src/style.scss',
                // 'src/assets'
            ],
            output: {
                chunkFileNames: 'js/[name].js',
                entryFileNames: 'js/[name].js',

                assetFileNames: ({ name }) => {
                    // if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
                    //     return 'images/[name][extname]';
                    // }

                    if (/\.css$/.test(name ?? '')) {
                        return 'css/[name][extname]';
                    }

                    // default value
                    // ref: https://rollupjs.org/guide/en/#outputassetfilenames
                    return '[name][extname]';
                },
            },
        },
    },
})