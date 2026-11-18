<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ==========================================
// BAGIAN: CONTROLLER AUTENTIKASI PENGGUNA (AUTH CONTROLLER)
// Menangani alur masuk sistem (Login 2 Peran), pendaftaran mandiri akun Customer (Register),
// serta pencabutan sesi token login (Logout).
// ==========================================
class AuthController extends Controller
{
    // ==========================================
    // BAGIAN 1: PROSES MASUK SISTEM (LOGIN)
    // Memvalidasi email dan kata sandi, lalu menerbitkan token akses Laravel Sanctum.
    // ==========================================
    public function login(Request $request)
    {
        // 1. Validasi input kredensial login
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // 2. Pencarian akun pengguna berdasarkan alamat email
        $user = User::where('email', $request->email)->first();

        // 3. Verifikasi kecocokan kata sandi dengan hash Bcrypt di database
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
            ], 401);
        }

        // 4. Menerbitkan Bearer Token baru untuk otorisasi permintaan berikutnya
        $token = $user->createToken('ganbook_auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil. Selamat datang kembali, ' . $user->name . '!',
            'token'   => $token,
            'user'    => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'role'    => $user->role,
                'phone'   => $user->phone,
                'address' => $user->address,
            ],
        ], 200);
    }

    // ==========================================
    // BAGIAN 2: PENDAFTARAN AKUN CUSTOMER (REGISTER)
    // Memungkinkan pengunjung toko mendaftar akun pelanggan baru secara mandiri.
    // ==========================================
    public function register(Request $request)
    {
        // 1. Validasi data formulir registrasi pelanggan
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.unique'       => 'Email tersebut telah terdaftar, silakan gunakan email lain.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // 2. Simpan record akun baru (peran otomatis ditetapkan sebagai 'customer')
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'customer',
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        // 3. Otomatis terbitkan token agar pelanggan langsung dalam keadaan login
        $token = $user->createToken('ganbook_auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran akun customer berhasil! Selamat datang di GanBook Sales.',
            'token'   => $token,
            'user'    => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'role'    => $user->role,
                'phone'   => $user->phone,
                'address' => $user->address,
            ],
        ], 201);
    }

    // ==========================================
    // BAGIAN 3: KELUAR SESI AKUN (LOGOUT)
    // Menghapus token akses aktif dari database untuk mengakhiri sesi pengguna.
    // ==========================================
    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Sesi Anda telah diakhiri.',
        ], 200);
    }

    // ==========================================
    // BAGIAN 4: TINJAUAN PROFIL LOGIN AKTIF (ME)
    // Mengambil rincian data akun pengguna yang sedang terautentikasi.
    // ==========================================
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user(),
        ], 200);
    }
}
