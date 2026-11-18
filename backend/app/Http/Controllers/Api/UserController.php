<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

// ==========================================
// BAGIAN: CONTROLLER MANAJEMEN PENGGUNA (USER CONTROLLER)
// Menampilkan dan menyaring daftar akun pengguna sistem (Administrator dan Customer).
// ==========================================
class UserController extends Controller
{
    // ==========================================
    // BAGIAN 1: MENAMPILKAN DAFTAR PENGGUNA (INDEX)
    // Mengambil daftar akun pengguna dengan filter opsi peran (Khusus Administrator).
    // ==========================================
    public function index(Request $request)
    {
        $query = User::select('id', 'name', 'email', 'role', 'phone', 'address', 'created_at')
            ->orderBy('id', 'asc');

        // Filter pengguna berdasarkan peran jika parameter role disertakan
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengguna berhasil diambil.',
            'data'    => $users,
        ], 200);
    }
}
