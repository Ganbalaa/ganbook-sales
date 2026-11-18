<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

// ==========================================
// BAGIAN: PERLINDUNGAN CSRF TOKEN
// Melindungi aplikasi dari serangan Cross-Site Request Forgery pada form web.
// ==========================================
class VerifyCsrfToken extends Middleware
{
    /**
     * Daftar URI yang dikecualikan dari verifikasi token CSRF (misalnya webhook atau rute API khusus).
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
