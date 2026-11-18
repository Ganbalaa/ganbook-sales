<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ==========================================
// BAGIAN: MODEL PRODUK KOMIK (BOOK MODEL)
// Mengelola data komik fisik (Manga, Manhwa, Manhua), harga, stok inventaris,
// file cover di public storage, dan relasi ke kategori komik.
// ==========================================
class Book extends Model
{
    use HasFactory;

    /**
     * Kolom tabel yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'comic_type',
        'genre',
        'year',
        'price',
        'stock',
        'cover_image',
        'description',
        'is_popular',
        'is_recommended',
    ];

    /**
     * Konversi tipe data otomatis (casting) untuk harga, stok, tahun, dan penanda boolean.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price'          => 'float',
        'stock'          => 'integer',
        'year'           => 'integer',
        'is_popular'     => 'boolean',
        'is_recommended' => 'boolean',
    ];

    /**
     * Menyertakan atribut kalkulasi cover_url ke dalam representasi JSON secara otomatis.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'cover_url',
    ];

    /**
     * Accessor URL publik cover komik.
     * Menghasilkan URL lengkap yang dapat diakses langsung oleh browser atau frontend Vite.
     */
    public function getCoverUrlAttribute(): string
    {
        if (empty($this->cover_image)) {
            return asset('assets/covers/one-piece.svg');
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, 'storage/')) {
            return asset($this->cover_image);
        }

        if (str_starts_with($this->cover_image, 'covers/')) {
            return asset('storage/' . $this->cover_image);
        }

        return $this->cover_image;
    }

    /**
     * Relasi Eloquent: Komik berada di bawah satu kategori utama (Many-to-One).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi Eloquent: Komik dapat tercatat dalam banyak rincian transaksi penjualan (One-to-Many).
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
