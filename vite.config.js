import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
    host: 'localhost', // o 'localhost'
    // para aceptar conexiones desde otros dominios
    cors: {
      origin: '*', // o '*', pero esto no es recomendable en producción
      // Opcionalmente, otros encabezados CORS
    },
  },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
