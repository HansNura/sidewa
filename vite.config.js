import {
    defineConfig,
    loadEnv
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ mode }) => {
    const isNetwork = process.env.NETWORK_MODE === 'true';

    // Load .env to get NETWORK_HOST
    const env = loadEnv(mode, process.cwd(), '');
    const networkHost = env.NETWORK_HOST || '192.168.0.243';

    const serverConfig = isNetwork
        ? {
            // Network mode: accessible from other devices
            host: '0.0.0.0',
            port: 5173,
            strictPort: true,
            cors: true,
            hmr: {
                host: networkHost,
            },
            origin: `http://${networkHost}:5173`,
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        }
        : {
            // Local mode: localhost only (default, secure)
            host: '127.0.0.1',
            port: 5173,
            cors: true,
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        };

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: serverConfig,
    };
});
