<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

// ==========================================
// BAGIAN: KEAMANAN PROXY & LOAD BALANCER
// Mengonfigurasi header proxy terpercaya untuk mendeteksi IP asli pengguna.
// ==========================================
class TrustProxies extends Middleware
{
    /**
     * Daftar proxy terpercaya untuk aplikasi ini.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * Header HTTP yang digunakan untuk mendeteksi informasi proxy.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
