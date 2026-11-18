<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// ==========================================
// BAGIAN: PENYEDIA LAYANAN EVENT & LISTENER
// Menghubungkan kejadian (Event) sistem dengan aksi penangan (Listener).
// ==========================================
class EventServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan antara Event dan Listener penangannya di aplikasi.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Mendaftarkan konfigurasi event kustom aplikasi.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Menentukan apakah event dan listener harus dideteksi secara otomatis.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
