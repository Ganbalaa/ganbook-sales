<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ==========================================
// BAGIAN: MODEL KATEGORI & GENRE KOMIK (CATEGORY MODEL)
// Mengelola klasifikasi tipe asal komik (Manga, Manhwa, Manhua) dan relasi ke daftar komik.
// ==========================================
class Category extends Model
{
    use HasFactory;

    /**
     * Kolom tabel yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    /**
     * Relasi Eloquent: Satu kategori menaungi banyak judul komik (One-to-Many).
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
