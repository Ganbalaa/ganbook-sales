<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

// ==========================================
// BAGIAN: MIDDLEWARE AUTENTIKASI PENGGUNA
// Memastikan setiap permintaan memiliki sesi atau token login yang sah.
// ==========================================
class Authenticate extends Middleware
{
    /**
     * Mengarahkan pengguna jika tidak terautentikasi.
     * Untuk permintaan API berbasis JSON, kembalikan nilai null agar response berupa JSON 401 Unauthorized.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
