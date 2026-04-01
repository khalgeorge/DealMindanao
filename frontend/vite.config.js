import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

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
        // Public pages
        main: resolve(__dirname, 'index.html'),
        shop: resolve(__dirname, 'shop.html'),
        product: resolve(__dirname, 'product.html'),
        cart: resolve(__dirname, 'cart.html'),
        checkout: resolve(__dirname, 'checkout.html'),
        orderConfirmed: resolve(__dirname, 'order-confirmed.html'),
        about: resolve(__dirname, 'about.html'),
        partner: resolve(__dirname, 'partner.html'),
        contact: resolve(__dirname, 'contact.html'),
        trustSafety: resolve(__dirname, 'trust-safety.html'),
        refunds: resolve(__dirname, 'refunds.html'),
        terms: resolve(__dirname, 'terms.html'),
        privacy: resolve(__dirname, 'privacy.html'),
        help: resolve(__dirname, 'help.html'),
        // Admin pages
        adminLogin: resolve(__dirname, 'admin/login.html'),
        adminDashboard: resolve(__dirname, 'admin/dashboard.html'),
        adminProducts: resolve(__dirname, 'admin/products.html'),
        adminOrders: resolve(__dirname, 'admin/orders.html'),
        adminCompanies: resolve(__dirname, 'admin/companies.html'),
        adminCategories: resolve(__dirname, 'admin/categories.html'),
        adminSettings: resolve(__dirname, 'admin/settings.html'),
        adminChat: resolve(__dirname, 'admin/chat.html'),
      },
    },
  },
});
