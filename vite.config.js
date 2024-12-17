import { v4wp } from '@kucrut/vite-for-wp';

export default {
    plugins: [
        v4wp({
            input: {
                front_main: 'src/js/front_main.ts',
                admin_main: 'src/js/admin_main.ts',
                lrseo_allposts: 'src/js/admin.ajax.allposts.js',
                lrseo_inbound_select_post: 'src/js/admin.ajax.inbound_select_post.js',
                lrseo_inbound_analyse_post: 'src/js/admin.ajax.inbound_analyse_post.js',
            },
            outDir: 'build', // Optional, defaults to 'dist'.
        }),
    ],
    server: {
        port: 3000,
        strictPort: true,
        // host: 'wp-dev.ddev.site',
        origin: 'https://wp-dev.ddev.site:3000',
    },
};
