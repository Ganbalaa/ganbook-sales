<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

// ==========================================
// BAGIAN: PENANGANAN MODE PEMELIHARAAN (MAINTENANCE)
// Menahan permintaan saat aplikasi sedang dalam mode perbaikan (artisan down).
// ==========================================
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * Daftar URI yang tetap diizinkan untuk diakses saat mode pemeliharaan aktif.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
