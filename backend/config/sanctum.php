<?php

// ==========================================
// BAGIAN 1: KONFIGURASI LARAVEL SANCTUM
// Mengatur otorisasi Bearer Token untuk otentikasi API frontend GanBook Sales.
// ==========================================

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Domain Stateful (Stateful Domains)
    |--------------------------------------------------------------------------
    | Domain yang menerima autentikasi berbasis cookie/sesi jika menggunakan SPA.
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        Sanctum::currentApplicationUrlWithPort()
    ))),

    /*
    |--------------------------------------------------------------------------
    | Guard Sanctum
    |--------------------------------------------------------------------------
    | Guard yang diperiksa saat memvalidasi request. Jika tidak cocok,
    | Sanctum memeriksa Bearer Token pada header Authorization request HTTP.
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Masa Berlaku Token (Menit)
    |--------------------------------------------------------------------------
    | Mengatur kedaluwarsa token. Jika null, token akses bersifat tetap aktif
    | hingga pengguna melakukan logout (token dihapus dari database).
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Middleware Sanctum
    |--------------------------------------------------------------------------
    */

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],

];
