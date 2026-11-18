<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel sales (header transaksi penjualan / order).
     */
    public function up(): void
    {
        // ==========================================
        // TABEL PENJUALAN & PESANAN (SALES / ORDERS)
        // ==========================================
        // Menyimpan data induk faktur / pesanan belanja e-commerce & transaksi toko
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();             // Nomor unik faktur/nota (contoh: INV-20261118-001)
            $table->foreignId('user_id')                            // Relasi ke user (customer / admin), nullable jika guest
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('customer_name')->default('Pelanggan Umum'); // Nama pembeli / pelanggan
            $table->string('customer_phone')->nullable();           // Nomor telepon / WhatsApp pelanggan
            $table->text('customer_address')->nullable();           // Alamat pengiriman lengkap
            $table->dateTime('sale_date');                          // Tanggal dan waktu transaksi
            $table->integer('total_items')->default(0);             // Total volume komik yang dibeli
            $table->decimal('total_price', 12, 2)->default(0);      // Total nominal belanja
            $table->string('payment_method')->default('cod');       // Metode: cod, qris
            $table->string('payment_status')->default('unpaid');    // Status pembayaran: paid, unpaid
            $table->string('order_status')->default('processing');  // Status pesanan: pending, processing, completed, cancelled
            $table->text('notes')->nullable();                      // Catatan tambahan pesanan
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
