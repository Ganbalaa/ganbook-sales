<?php

// ==========================================
// BAGIAN 1: KONFIGURASI CORS (CROSS-ORIGIN RESOURCE SHARING)
// Mengatur izin akses API dari frontend (Vite / Browser) ke backend Laravel.
// ==========================================

return [

    /*
    |--------------------------------------------------------------------------
    | Jalur Rute yang Diizinkan (Allowed Paths)
    |--------------------------------------------------------------------------
    | Menentukan endpoint mana saja yang mendukung permintaan lintas origin.
    | Seluruh rute API ('api/*') dan cookie CSRF Sanctum diizinkan.
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Metode HTTP yang Diizinkan (Allowed HTTP Methods)
    |--------------------------------------------------------------------------
    | Mengizinkan semua metode standar seperti GET, POST, PUT, DELETE, OPTIONS.
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Domain / Asal Frontend yang Diizinkan (Allowed Origins)
    |--------------------------------------------------------------------------
    | Mengizinkan browser dari domain frontend (seperti http://localhost:5173)
    | untuk berkomunikasi dan meminta data ke API backend Laravel.
    */

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Header Permintaan yang Diizinkan (Allowed Request Headers)
    |--------------------------------------------------------------------------
    | Mengizinkan seluruh header kustom, seperti Authorization (Bearer Token),
    | Content-Type, dan Accept JSON.
    */

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
