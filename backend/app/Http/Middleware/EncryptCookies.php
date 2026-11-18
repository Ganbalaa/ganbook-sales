<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

// ==========================================
// BAGIAN: ENKRIPSI COOKIE APLIKASI
// Mengamankan seluruh cookie aplikasi dengan enkripsi bawaan Laravel.
// ==========================================
class EncryptCookies extends Middleware
{
    /**
     * Daftar nama cookie yang dikecualikan dari proses enkripsi.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
