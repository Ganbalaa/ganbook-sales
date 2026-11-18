<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// ==========================================
// SEEDER UTAMA (DATABASE SEEDER)
// ==========================================
// Menjalankan seluruh seeder secara berurutan sesuai relasi database
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan database seeder aplikasi.
     */
    public function run(): void
    {
        // 1. Mengisi data pengguna (Admin & Customer)
        $this->call(UserSeeder::class);

        // 2. Mengisi data kategori buku
        $this->call(CategorySeeder::class);

        // 3. Mengisi katalog data buku awal
        $this->call(BookSeeder::class);

        // 4. Mengisi contoh transaksi awal untuk demo dashboard
        $this->call(SaleSeeder::class);
    }
}
