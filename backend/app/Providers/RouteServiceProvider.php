<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

// ==========================================
// BAGIAN: PENYEDIA LAYANAN RUTE (ROUTE SERVICE PROVIDER)
// Mengatur pemetaan berkas rute API & Web serta pembatasan kuota request (Rate Limiting).
// ==========================================
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur default halaman "home" setelah proses autentikasi berhasil.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Mengatur pemetaan rute API, rute Web, dan pembatasan frekuensi akses API.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Rute API diberi awalan 'api/' dan middleware 'api'
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rute Web menggunakan middleware 'web'
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Mengonfigurasi pembatas laju permintaan (Rate Limiter) untuk endpoint API.
     * Secara bawaan membatasi 60 request per menit per alamat IP / ID pengguna.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
