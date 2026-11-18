<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Book;
use App\Models\User;

// ==========================================
// SEEDER TRANSAKSI PENJUALAN KOMIK (SALE SEEDER)
// ==========================================
// Menyiapkan contoh transaksi pesanan customer untuk pengujian dashboard & riwayat
class SaleSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        $customer = User::whereIn('role', ['customer', 'pelanggan'])->first();

        $book1 = Book::where('title', 'like', '%One Piece%')->first();
        $book2 = Book::where('title', 'like', '%Solo Leveling%')->first();
        $book3 = Book::where('title', 'like', '%Frieren%')->first();

        // 1. Contoh Pesanan Customer 1 (Metode QRIS - Selesai)
        if ($customer && $book1 && $book2) {
            $qty1 = 1;
            $qty2 = 2;
            $price1 = $book1->price;
            $price2 = $book2->price;
            $totalItems = $qty1 + $qty2;
            $totalPrice = ($qty1 * $price1) + ($qty2 * $price2);

            $sale1 = Sale::create([
                'invoice_number'   => 'INV-' . date('Ymd') . '-0001',
                'user_id'          => $customer->id,
                'customer_name'    => $customer->name,
                'customer_phone'   => $customer->phone,
                'customer_address' => $customer->address,
                'sale_date'        => now()->subHours(5),
                'total_items'      => $totalItems,
                'total_price'      => $totalPrice,
                'payment_method'   => 'qris',
                'payment_status'   => 'paid',
                'order_status'     => 'completed',
                'notes'            => 'Pesanan online customer komik One Piece & Solo Leveling',
            ]);

            SaleDetail::create([
                'sale_id'    => $sale1->id,
                'book_id'    => $book1->id,
                'quantity'   => $qty1,
                'unit_price' => $price1,
                'subtotal'   => $qty1 * $price1,
            ]);

            SaleDetail::create([
                'sale_id'    => $sale1->id,
                'book_id'    => $book2->id,
                'quantity'   => $qty2,
                'unit_price' => $price2,
                'subtotal'   => $qty2 * $price2,
            ]);
        }

        // 2. Contoh Pesanan Customer 2 (Metode COD - Diproses)
        if ($customer && $book3) {
            $qty3 = 1;
            $price3 = $book3->price;

            $sale2 = Sale::create([
                'invoice_number'   => 'INV-' . date('Ymd') . '-0002',
                'user_id'          => $customer->id,
                'customer_name'    => $customer->name,
                'customer_phone'   => $customer->phone,
                'customer_address' => $customer->address,
                'sale_date'        => now()->subHour(1),
                'total_items'      => $qty3,
                'total_price'      => $qty3 * $price3,
                'payment_method'   => 'cod',
                'payment_status'   => 'unpaid',
                'order_status'     => 'processing',
                'notes'            => 'Pesanan COD komik Frieren',
            ]);

            SaleDetail::create([
                'sale_id'    => $sale2->id,
                'book_id'    => $book3->id,
                'quantity'   => $qty3,
                'unit_price' => $price3,
                'subtotal'   => $qty3 * $price3,
            ]);
        }
    }
}
