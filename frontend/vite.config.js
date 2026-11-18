// ==========================================
// BAGIAN 1: KONFIGURASI VITE DEVELOPMENT SERVER
// Mengatur server lokal frontend, port Vite (5173), dan bundling multi-halaman HTML.
// ==========================================

import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  // ==========================================
  // BAGIAN 2: PENGATURAN SERVER PENGEMBANGAN
  // Mengatur port dan host lokal untuk pengujian aplikasi pada browser.
  // ==========================================
  root: '.',
  server: {
    port: 5173,
    host: '127.0.0.1',
    open: false
  },

  // ==========================================
  // BAGIAN 3: TITIK MASUK MULTI-HALAMAN (ROLLUP INPUT)
  // Mendaftarkan seluruh berkas HTML frontend agar dapat di-build dengan benar.
  // ==========================================
  build: {
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'index.html'),
        login: resolve(__dirname, 'login.html'),
        register: resolve(__dirname, 'register.html'),
        catalog: resolve(__dirname, 'catalog.html'),
        cart: resolve(__dirname, 'cart.html'),
        checkout: resolve(__dirname, 'checkout.html'),
        orders: resolve(__dirname, 'orders.html'),
        dashboard: resolve(__dirname, 'dashboard.html'),
        books: resolve(__dirname, 'books.html'),
        categories: resolve(__dirname, 'categories.html'),
        sales: resolve(__dirname, 'sales.html'),
        users: resolve(__dirname, 'users.html'),
      }
    }
  }
});
