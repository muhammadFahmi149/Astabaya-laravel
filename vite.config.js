import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/dashboard/inflasi.css',
                'resources/js/dashboard/inflasi.js',
                'resources/css/dashboard/kemiskinan.css',
                'resources/js/dashboard/kemiskinan.js',
                'resources/css/dashboard/kependudukan.css',
                'resources/js/dashboard/kependudukan.js',
                'resources/css/dashboard/gini-ratio.css',
                'resources/js/dashboard/gini-ratio.js',
                'resources/css/dashboard/hotel-occupancy.css',
                'resources/js/dashboard/hotel-occupancy.js',
                'resources/css/dashboard/pdrb-pengeluaran.css',
                'resources/css/dashboard/indeks-pembangunan-manusia.css',
                'resources/js/dashboard/pdrb-pengeluaran.js',
                'resources/js/dashboard/indeks-pembangunan-manusia.js',
                'resources/js/utilities.js',
                'resources/css/dashboard/indikator-ipm.css',
                'resources/js/dashboard/indikator-ipm.js',
                'resources/css/dashboard/ketenagakerjaan.css',
                'resources/js/dashboard/ketenagakerjaan.js',
                'resources/css/dashboard/pdrb-lapangan-usaha.css',
                'resources/js/dashboard/pdrb-lapangan-usaha.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});




