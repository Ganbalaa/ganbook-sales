<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Exception;

// ==========================================
// BAGIAN: CONTROLLER TRANSAKSI PENJUALAN (SALE CONTROLLER)
// Menangani pemesanan checkout komik online oleh Customer, pengurangan stok otomatis
// dengan Database Transaction & lockForUpdate, serta pengelolaan pesanan oleh Administrator.
// ==========================================
class SaleController extends Controller
{
    // ==========================================
    // BAGIAN 1: MENAMPILKAN RIWAYAT TRANSAKSI PENJUALAN (INDEX)
    // - Administrator dapat melihat seluruh transaksi toko untuk laporan penjualan.
    // - Customer hanya dapat melihat daftar pesanan miliknya sendiri.
    // ==========================================
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi diperlukan.',
            ], 401);
        }

        $query = Sale::with(['user', 'saleDetails.book'])->latest();

        // Jika bukan admin (customer), batasi hanya pesanan milik pengguna yang bersangkutan
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $sales = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi penjualan berhasil diambil.',
            'data'    => $sales,
        ], 200);
    }

    // ==========================================
    // BAGIAN 2: MEMBUAT TRANSAKSI PENJUALAN BARU / CHECKOUT (STORE)
    // Menjalankan transaksi database (DB Transaction) dengan penguncian baris (lockForUpdate)
    // agar penyimpanan transaksi dan pemotongan stok komik konsisten dan bebas dari race condition.
    // ==========================================
    public function store(Request $request)
    {
        // 1. Pastikan pengguna telah terautentikasi (wajib login sebelum checkout)
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melakukan checkout pesanan.',
            ], 401);
        }

        // 2. Validasi kelengkapan data pengiriman, metode pembayaran, dan item belanja
        $request->validate([
            'customer_name'     => 'required|string|max:100',
            'customer_phone'    => 'required|string|max:25',
            'customer_address'  => 'required|string|max:255',
            'payment_method'    => 'required|in:qris,cod,tunai',
            'items'             => 'required|array|min:1',
            'items.*.book_id'   => 'required|exists:books,id',
            'items.*.quantity'  => 'required|integer|min:1',
            'notes'             => 'nullable|string|max:255',
        ], [
            'customer_name.required'    => 'Nama lengkap penerima wajib diisi.',
            'customer_phone.required'   => 'Nomor telepon / WhatsApp wajib diisi.',
            'customer_address.required' => 'Alamat lengkap pengiriman wajib diisi.',
            'payment_method.required'   => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'         => 'Metode pembayaran harus berupa QRIS, COD, atau Tunai.',
            'items.required'            => 'Keranjang pesanan minimal harus memiliki 1 item komik.',
            'items.*.book_id.required'  => 'Komik yang dipesan wajib dipilih.',
            'items.*.quantity.min'      => 'Jumlah kuantitas komik minimal 1 buku.',
        ]);

        // 3. Mulai Database Transaction untuk menjamin konsistensi data secara atomik
        DB::beginTransaction();

        try {
            $customerName = $request->customer_name;
            $customerPhone = $request->customer_phone;
            $customerAddress = $request->customer_address;
            $paymentMethod = $request->payment_method;

            // Penetapan status awal transaksi berdasarkan metode pembayaran
            $paymentStatus = ($paymentMethod === 'qris' || $paymentMethod === 'tunai') ? 'paid' : 'unpaid';
            $orderStatus = ($paymentMethod === 'qris' || $paymentMethod === 'tunai') ? 'completed' : 'processing';

            // Pembuatan nomor faktur / invoice unik (contoh: INV-20261118-A1B2)
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $totalPrice = 0;
            $totalItems = 0;
            $itemsToProcess = [];

            // 4. Periksa ketersediaan stok buku dengan penguncian baris (lockForUpdate)
            foreach ($request->items as $item) {
                $book = Book::lockForUpdate()->find($item['book_id']);

                if (!$book) {
                    throw new Exception("Komik dengan ID {$item['book_id']} tidak ditemukan.");
                }

                $qty = (int) $item['quantity'];

                // Tolak transaksi jika kuantitas pesanan melebihi sisa stok fisik
                if ($book->stock < $qty) {
                    throw new Exception("Stok komik '{$book->title}' tidak mencukupi! Sisa stok: {$book->stock}, dipesan: {$qty}.");
                }

                $subtotal = $qty * (float) $book->price;
                $totalPrice += $subtotal;
                $totalItems += $qty;

                $itemsToProcess[] = [
                    'book'     => $book,
                    'quantity' => $qty,
                    'price'    => (float) $book->price,
                    'subtotal' => $subtotal,
                ];
            }

            // 5. Simpan data induk transaksi penjualan (Header Faktur)
            $sale = Sale::create([
                'invoice_number'   => $invoiceNumber,
                'user_id'          => $user->id,
                'customer_name'    => $customerName,
                'customer_phone'   => $customerPhone,
                'customer_address' => $customerAddress,
                'sale_date'        => now(),
                'total_items'      => $totalItems,
                'total_price'      => $totalPrice,
                'payment_method'   => $paymentMethod,
                'payment_status'   => $paymentStatus,
                'order_status'     => $orderStatus,
                'notes'            => $request->notes,
            ]);

            // 6. Simpan rincian item buku (SaleDetail) dan kurangi stok komik secara otomatis
            foreach ($itemsToProcess as $item) {
                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'book_id'    => $item['book']->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                // Pengurangan stok fisik buku komik secara otomatis di database
                $item['book']->decrement('stock', $item['quantity']);
            }

            // 7. Simpan seluruh perubahan secara permanen (Commit Transaksi)
            DB::commit();

            $sale->load(['user', 'saleDetails.book']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diproses. Stok komik telah diperbarui.',
                'data'    => $sale,
            ], 201);

        } catch (Exception $e) {
            // Batalkan seluruh perubahan jika terjadi kegagalan (Rollback Transaksi)
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage(),
            ], 422);
        }
    }

    // ==========================================
    // BAGIAN 3: MELIHAT DETAIL SATU PESANAN / NOTA (SHOW)
    // Mengambil rincian faktur penjualan untuk dicetak atau ditinjau detailnya.
    // ==========================================
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi diperlukan.',
            ], 401);
        }

        $sale = Sale::with(['user', 'saleDetails.book.category'])->find($id);

        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'Data pesanan tidak ditemukan.',
            ], 404);
        }

        // Batasi akses: Customer hanya boleh melihat pesanan miliknya sendiri
        if (!$user->isAdmin() && $sale->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk melihat pesanan ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil.',
            'data'    => $sale,
        ], 200);
    }

    // ==========================================
    // BAGIAN 4: MEMPERBARUI STATUS PESANAN (UPDATE STATUS)
    // Mengubah status pesanan atau status pembayaran oleh Administrator.
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'payment_status' => 'nullable|in:paid,unpaid',
            'order_status'   => 'nullable|in:pending,processing,completed,cancelled',
        ]);

        if ($request->filled('payment_status')) {
            $sale->payment_status = $request->payment_status;
        }

        if ($request->filled('order_status')) {
            $sale->order_status = $request->order_status;
        }

        $sale->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui oleh Administrator.',
            'data'    => $sale,
        ], 200);
    }
}
