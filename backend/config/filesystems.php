<?php

// ==========================================
// BAGIAN 1: KONFIGURASI PENYIMPANAN BERKAS (FILESYSTEM & UPLOAD)
// Mengatur disk penyimpanan berkas lokal dan folder publik untuk cover komik.
// ==========================================

return [

    /*
    |--------------------------------------------------------------------------
    | Disk Default Aplikasi
    |--------------------------------------------------------------------------
    | Menentukan disk penyimpanan utama yang digunakan secara default.
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Disk Penyimpanan (Filesystem Disks)
    |--------------------------------------------------------------------------
    | Mengatur folder target tempat penyimpanan file.
    | Disk 'public' mengarah ke 'storage/app/public' yang dapat diakses publik
    | melalui URL symlink '/storage/...'.
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Tautan Simbolis (Symbolic Links / Storage Link)
    |--------------------------------------------------------------------------
    | Tautan yang dibuat saat menjalankan perintah 'php artisan storage:link'.
    | Menghubungkan 'public/storage' ke 'storage/app/public' agar cover komik
    | yang diunggah Admin dapat diakses langsung oleh browser frontend.
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
