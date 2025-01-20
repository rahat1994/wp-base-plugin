import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import autoprefixer from "autoprefixer";
import tailwind from "tailwindcss";

// https://vitejs.dev/config/
export default defineConfig(({ mode = "admin" }) => {
    if (mode === "frontend") {
        return {
            server: {
                port: 9990,
                strictPort: true,
                hmr: {
                    port: 9990,
                    host: "localhost",
                    protocol: "ws",
                },
            },
            build: {
                manifest: true,
                outDir: "assets/frontend",
                assetsDir: "assetsDIR",
                emptyOutDir: false, //dont delete the contents of the output directory before each build
                // https://rollupjs.org/guide/en/#big-list-of-options
                rollupOptions: {
                    input: [
                        "resources/frontend/base.js",
                        "resources/frontend/base.css",
                    ],
                    output: {
                        chunkFileNames: "js/[name].js",
                        entryFileNames: "js/[name].js",

                        assetFileNames: ({ name }) => {
                            // if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
                            //     return 'images/[name][extname]';
                            // }

                            if (/\.css$/.test(name ?? "")) {
                                return "css/[name][extname]";
                            }

                            // default value
                            // ref: https://rollupjs.org/guide/en/#outputassetfilenames
                            return "[name][extname]";
                        },
                    },
                },
            },
        };
    }

    return {
        plugins: [vue()],
        server: {
            port: 8880,
            strictPort: true,
            hmr: {
                port: 8880,
                host: "localhost",
                protocol: "ws",
            },
        },
        build: {
            manifest: true,
            outDir: "assets",
            assetsDir: "assetsDIR",
            // publicDir: 'public',
            emptyOutDir: false, //dont delete the contents of the output directory before each build

            // https://rollupjs.org/guide/en/#big-list-of-options
            rollupOptions: {
                input: [
                    "resources/admin/main.js",
                    // 'src/style.scss',
                    // 'src/assets'
                ],
                output: {
                    chunkFileNames: "js/[name].js",
                    entryFileNames: "js/[name].js",

                    assetFileNames: ({ name }) => {
                        // if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
                        //     return 'images/[name][extname]';
                        // }

                        if (/\.css$/.test(name ?? "")) {
                            return "css/[name][extname]";
                        }

                        // default value
                        // ref: https://rollupjs.org/guide/en/#outputassetfilenames
                        return "[name][extname]";
                    },
                },
            },
        },
        css: {
            postcss: {
                plugins: [tailwind(), autoprefixer()],
            },
        },
        resolve: {
            alias: {
                "@": fileURLToPath(
                    new URL("./resources/admin", import.meta.url)
                ),
            },
        },
    };
});
