<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// BAGIAN: RUTE WEB LARAVEL
// Mengatur rute berbasis sesi browser bawaan Laravel (jika diperlukan).
// Frontend GanBook Sales menggunakan arsitektur terpisah via Vite di port 5173.
// ==========================================

Route::get('/', function () {
    return response()->json([
        'app'     => 'GanBook Sales REST API',
        'version' => '1.0.0',
        'status'  => 'Server backend Laravel aktif dan berjalan dengan baik.',
    ]);
});
