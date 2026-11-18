<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// ==========================================
// BAGIAN: PENYEDIA LAYANAN AUTENTIKASI (AUTH SERVICE PROVIDER)
// Mendaftarkan pemetaan Policy dan aturan Gate otorisasi aplikasi.
// ==========================================
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan model database ke kelas Policy otorisasi.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Mendaftarkan layanan autentikasi dan aturan otorisasi pengguna.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
