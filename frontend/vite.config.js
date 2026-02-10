import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
  server: {
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    rollupOptions: {
      input: {
        main: './index.html',
        shop: './shop.html',
        product: './product.html',
        checkout: './checkout.html',
        dashboard: './dashboard.html',
        profile: './profile.html',
        login: './auth/login.html',
        register: './auth/register.html',
      },
    },
  },
});
