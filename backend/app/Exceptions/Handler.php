<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// ==========================================
// BAGIAN: PENANGANAN KESALAHAN & EXCEPTION GLOBAL
// Mengelola pencatatan log error dan format respons saat terjadi error di aplikasi.
// ==========================================
class Handler extends ExceptionHandler
{
    /**
     * Daftar tipe exception dengan level log kustom yang sesuai.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * Daftar tipe exception yang tidak perlu dicatat ke dalam file log sistem.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Daftar nama input formulir yang tidak boleh disimpan ke session saat terjadi validasi gagal (keamanan data).
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Mendaftarkan fungsi callback penanganan exception khusus untuk aplikasi.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
