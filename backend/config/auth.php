<?php

// ==========================================
// BAGIAN 1: KONFIGURASI AUTENTIKASI PENGGUNA
// Mengatur guard autentikasi, penyedia data model User, dan pengelolaan token/password.
// ==========================================

return [

    /*
    |--------------------------------------------------------------------------
    | Default Guard Autentikasi
    |--------------------------------------------------------------------------
    | Guard bawaan aplikasi menggunakan provider 'users'.
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Guard Autentikasi Aplikasi (Authentication Guards)
    |--------------------------------------------------------------------------
    | Mendefinisikan mekanisme verifikasi sesi login pengguna.
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Penyedia Data Pengguna (User Providers)
    |--------------------------------------------------------------------------
    | Menghubungkan sistem autentikasi ke Model Eloquent App\Models\User.
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Reset Password
    |--------------------------------------------------------------------------
    | Mengatur tabel token dan masa berlaku token pemulihan kata sandi (60 menit).
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Batas Waktu Konfirmasi Password
    |--------------------------------------------------------------------------
    | Jumlah detik sebelum sesi verifikasi ulang kata sandi kedaluwarsa.
    */

    'password_timeout' => 10800,

];
