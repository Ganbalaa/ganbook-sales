<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

// ==========================================
// BAGIAN: PEMBERSIHAN SPASI INPUT (TRIM STRINGS)
// Otomatis menghapus spasi di awal dan akhir pada seluruh input teks pengguna.
// ==========================================
class TrimStrings extends Middleware
{
    /**
     * Daftar nama atribut input yang tidak boleh dipotong spasinya (misal kata sandi).
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
