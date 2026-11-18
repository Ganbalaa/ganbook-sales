<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// ==========================================
// BAGIAN: MIDDLEWARE PEMERIKSAAN PERAN PENGGUNA (ROLE GUARD)
// Membatasi akses endpoint API berdasarkan peran pengguna (Admin atau Customer).
// ==========================================
class CheckRole
{
    /**
     * Menangani permintaan HTTP yang masuk dan memvalidasi hak akses peran pengguna.
     *
     * @param  \Illuminate\Http\Request  $request Objek permintaan dari pengguna
     * @param  \Closure  $next Fungsi kelanjutan pipeline middleware
     * @param  string  ...$roles Daftar peran yang diberikan hak akses (contoh: 'admin', 'customer')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1. Verifikasi apakah pengguna sudah dalam kondisi login
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi diperlukan. Silakan login terlebih dahulu.',
            ], 401);
        }

        $userRole = $user->role;
        // Normalisasi alias nama peran customer / pelanggan
        if ($userRole === 'pelanggan') {
            $userRole = 'customer';
        }

        // 2. Evaluasi kesesuaian peran akun pengguna dengan daftar peran yang diizinkan
        if (!in_array($userRole, $roles) && !in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Peran akun Anda (' . strtoupper($user->role) . ') tidak memiliki izin untuk mengakses fitur ini.',
            ], 403);
        }

        // 3. Lanjutkan permintaan ke controller jika peran sesuai
        return $next($request);
    }
}
