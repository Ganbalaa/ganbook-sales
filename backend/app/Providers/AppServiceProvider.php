<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ==========================================
// BAGIAN: PENYEDIA LAYANAN APLIKASI (APP SERVICE PROVIDER)
// Tempat mendaftarkan binding layanan dan inisialisasi awal aplikasi.
// ==========================================
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan layanan ketergantungan (dependency injection) ke dalam container aplikasi.
     */
    public function register(): void
    {
        //
    }

    /**
     * Menginisialisasi konfigurasi awal saat seluruh layanan aplikasi telah siap dijalankan.
     */
    public function boot(): void
    {
        //
    }
}
