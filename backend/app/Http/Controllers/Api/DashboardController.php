<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;

// ==========================================
// BAGIAN: CONTROLLER DASHBOARD ADMINISTRATOR (DASHBOARD CONTROLLER)
// Menghitung data analitik toko komik: total buku, kategori, pelanggan, transaksi, dan total pendapatan.
// ==========================================
class DashboardController extends Controller
{
    // ==========================================
    // BAGIAN 1: STATISTIK OPERASIONAL TOKO KOMIK
    // Mengagregasi metrik kinerja penjualan dan ringkasan status pesanan untuk halaman dashboard admin.
    // ==========================================
    public function adminStats()
    {
        // 1. Perhitungan metrik ringkasan kartu statistik utama
        $totalComics = Book::count();
        $totalCategories = Category::count();
        $totalCustomers = User::whereIn('role', ['customer', 'pelanggan'])->count();
        $totalSales = Sale::count();
        $totalRevenue = Sale::sum('total_price') ?? 0;

        // 2. Kalkulasi distribusi komik per tipe asal (Manga Jepang, Manhwa Korea, Manhua China)
        $mangaCount = Book::where('comic_type', 'Manga')->count();
        $manhwaCount = Book::where('comic_type', 'Manhwa')->count();
        $manhuaCount = Book::where('comic_type', 'Manhua')->count();

        // 3. Ringkasan status transaksi pesanan
        $completedSales = Sale::where('order_status', 'completed')->count();
        $processingSales = Sale::where('order_status', 'processing')->count();

        // 4. Mengambil 5 transaksi penjualan terbaru beserta relasi user dan buku
        $recentSales = Sale::with(['user', 'saleDetails.book'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistik dashboard admin berhasil diambil.',
            'data'    => [
                'total_comics'      => (int) $totalComics,
                'total_categories'  => (int) $totalCategories,
                'total_customers'   => (int) $totalCustomers,
                'total_sales'       => (int) $totalSales,
                'total_revenue'     => (float) $totalRevenue,
                'comics_by_type'    => [
                    'manga'  => $mangaCount,
                    'manhwa' => $manhwaCount,
                    'manhua' => $manhuaCount,
                ],
                'orders_summary'    => [
                    'completed'  => $completedSales,
                    'processing' => $processingSales,
                ],
                'recent_sales'      => $recentSales,
            ],
        ], 200);
    }
}
