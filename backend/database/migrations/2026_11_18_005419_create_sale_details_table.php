<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel sale_details (rincian buku per transaksi).
     */
    public function up(): void
    {
        // ==========================================
        // TABEL DETAIL PENJUALAN (SALE_DETAILS)
        // ==========================================
        // Menyimpan rincian item-item buku yang dibeli dalam satu transaksi
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')                          // Foreign key ke tabel sales
                  ->constrained('sales')
                  ->onDelete('cascade');
            $table->foreignId('book_id')                          // Foreign key ke tabel books
                  ->constrained('books')
                  ->onDelete('cascade');
            $table->integer('quantity');                          // Jumlah buku yang dibeli
            $table->decimal('unit_price', 12, 2);                 // Harga satuan saat transaksi dilakukan
            $table->decimal('subtotal', 12, 2);                   // Subtotal harga (quantity * unit_price)
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
