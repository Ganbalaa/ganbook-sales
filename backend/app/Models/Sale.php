<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ==========================================
// BAGIAN: MODEL TRANSAKSI PENJUALAN & PESANAN (SALE MODEL)
// Mengelola data induk transaksi e-commerce, nomor faktur/invoice unik, status pembayaran,
// status pesanan, dan relasi ke detail item komik yang dipesan.
// ==========================================
class Sale extends Model
{
    use HasFactory;

    /**
     * Kolom tabel yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'sale_date',
        'total_items',
        'total_price',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
    ];

    /**
     * Konversi tipe data otomatis (casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sale_date'   => 'datetime',
        'total_items' => 'integer',
        'total_price' => 'float',
    ];

    /**
     * Relasi Eloquent: Transaksi terhubung ke akun pengguna pemesan (Customer atau Admin).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Eloquent: Satu transaksi penjualan memiliki banyak rincian item komik yang dibeli (One-to-Many).
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }
}
