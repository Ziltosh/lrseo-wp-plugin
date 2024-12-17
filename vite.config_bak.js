import {defineConfig} from 'vite';
import {viteWPConfig} from '@wp-strap/vite';

export default defineConfig({
    base: '',
    build: {
        target: 'es2015',
        manifest: 'manifest.json',
        watch: {
            chokidar: {
                usePolling: true,
                useFsEvents: false
            },
            include: ['src/**'],
        },
        rollupOptions: {
        }
    },
    plugins: [
        viteWPConfig({
            assets: {
                hash: false,
                rules: {
                    fonts: /ttf|woff|woff2/i,
                }
            },
            bundles: {
                banner: '',
                footer: '',
            }
        })
        // viteCopyAssetFiles({
        //     rules: {
        //         fonts: /ttf|woff|woff2/i,
        //     },
        //     // root: 'src',
        //     // outDir: 'build',
        //     // entry: 'Static',
        //     /* On mets une règle pour les fonts car il semble y avoir un bug au niveau du traitement postcss des fonts,
        // il va pas les chercher au bon endroit */
        //     // assets: {
        //     //     hash: false,
        //     //     rules: {
        //     //         fonts: /ttf|woff|woff2/i,
        //     //     },
        //     // },
        // }),
        // ViteImageOptimizer(),
        // viteEncapsulateBundles({
        //     banner: '',
        //     footer: '',
        // }),
        // viteHandleHotUpdate()
        // liveReload(['./src/**/*.{php,html,css,pcss,js,jsx,ts,tsx,json}'])
    ],
    server: {
        port: 3000,
        origin: 'https://wp-dev.ddev.site:3000',
    },
});
