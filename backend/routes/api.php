<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UserController;

// ==========================================
// BAGIAN UTAMA: RUTE RESTFUL API GANBOOK SALES
// Menghubungkan frontend antarmuka pengguna dengan backend Laravel.
// Menggunakan arsitektur 2 Peran Utama: Administrator dan Customer.
// ==========================================

// ==========================================
// BAGIAN 1: RUTE PUBLIK (PENGUNJUNG TANPA LOGIN)
// Endpoint yang dapat diakses oleh pengunjung umum tanpa autentikasi.
// ==========================================

// Autentikasi: Masuk (Login) dan Pendaftaran Mandiri Akun Customer (Register)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Katalog Komik & Kategori: Menjelajahi daftar komik, filter tipe (Manga/Manhwa/Manhua), dan detail komik
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// ==========================================
// BAGIAN 2: RUTE TERLINDUNGI (MEMERLUKAN LOGIN / TOKEN SANCTUM)
// Endpoint yang mewajibkan token Bearer hasil login yang valid.
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    // Sesi Pengguna: Keluar akun (Logout) dan periksa data profil login (Me)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Transaksi Penjualan / Checkout Pesanan:
    // Pengunjung wajib login sebagai Customer sebelum membuat pesanan
    Route::post('/sales', [SaleController::class, 'store']);

    // Riwayat Pesanan:
    // Customer hanya melihat pesanannya sendiri, Admin dapat melihat seluruh transaksi toko
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/sales/{id}', [SaleController::class, 'show']);

    // ==========================================
    // BAGIAN 3: RUTE KHUSUS ADMINISTRATOR (ROLE: ADMIN)
    // Hanya dapat diakses oleh akun dengan peran 'admin' melalui CheckRole Middleware.
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        // Statistik Analitik Toko: Ringkasan total komik, kategori, transaksi, dan omzet pendapatan
        Route::get('/dashboard', [DashboardController::class, 'adminStats']);
        Route::get('/dashboard/admin', [DashboardController::class, 'adminStats']);

        // Manajemen Status Pesanan: Admin dapat memperbarui status pesanan & status pembayaran
        Route::patch('/sales/{id}/status', [SaleController::class, 'updateStatus']);

        // Manajemen Inventaris Komik (CRUD): Tambah komik & upload cover, perbarui data, hapus komik
        Route::post('/books', [BookController::class, 'store']);
        Route::match(['put', 'post'], '/books/{id}', [BookController::class, 'update']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);

        // Manajemen Kategori & Genre Komik (CRUD): Tambah kategori, ubah data, dan hapus kategori
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Manajemen Pengguna Sistem: Meninjau daftar akun Administrator dan Customer terdaftar
        Route::get('/users', [UserController::class, 'index']);
    });
});
