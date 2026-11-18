<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// ==========================================
// BAGIAN: MODEL PENGGUNA (USER MODEL)
// Mengelola data akun pengguna, autentikasi Sanctum, pembagian 2 peran (Admin & Customer),
// serta relasi riwayat transaksi penjualan.
// ==========================================
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom tabel yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * Kolom data yang disembunyikan saat data diserialisasi ke JSON/array (keamanan kata sandi).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data otomatis (casting) untuk atribut tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi Eloquent: Seorang user Customer dapat memiliki banyak transaksi pemesanan komik (One-to-Many).
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Helper pengecekan apakah pengguna memiliki peran Administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper pengecekan apakah pengguna memiliki peran Customer / Pelanggan.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer' || $this->role === 'pelanggan';
    }
}
