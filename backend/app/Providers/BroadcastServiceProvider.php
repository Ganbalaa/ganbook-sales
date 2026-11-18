<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

// ==========================================
// BAGIAN: PENYEDIA LAYANAN BROADCAST WEBSOCKET
// Mengatur rute saluran siaran real-time (WebSockets) untuk aplikasi.
// ==========================================
class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan rute otorisasi channel broadcast.
     */
    public function boot(): void
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
