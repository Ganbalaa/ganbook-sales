<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ==========================================
// BAGIAN: MODEL RINCIAN ITEM TRANSAKSI (SALE DETAIL MODEL)
// Mengelola rincian setiap item buku komik dalam satu nomor faktur transaksi,
// mencakup kuantitas pembelian, harga satuan saat transaksi, dan subtotal harga.
// ==========================================
class SaleDetail extends Model
{
    use HasFactory;

    /**
     * Kolom tabel yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'book_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * Konversi tipe data otomatis (casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'float',
        'subtotal'   => 'float',
    ];

    /**
     * Relasi Eloquent: Rincian item merujuk ke data induk faktur penjualan (Many-to-One).
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Relasi Eloquent: Rincian item merujuk ke data master komik yang dibeli (Many-to-One).
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
