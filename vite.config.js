import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

//import { defineConfig } from 'vite';
//import laravel from 'laravel-vite-plugin';
//
//export default defineConfig({
//    plugins: [
//        laravel({
//            input: ['resources/css/app.css', 'resources/js/app.js'],
//            refresh: true,
//        }),
//    ],
//    server: {
//        host: '0.0.0.0',
//        port: 5173,
//        strictPort: true,
//        cors: true,
//        hmr: {
//            host: 't2z89zhx-5173.asse.devtunnels.ms', // ← ganti ke port 5173, bukan 8000
//            protocol: 'wss',
//            clientPort: 443,
//        },
//    },
//});