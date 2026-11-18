<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ==========================================
// SEEDER PENGGUNA TOKO KOMIK (USER SEEDER)
// ==========================================
// Menyiapkan 2 akun peran utama: Administrator dan Customer untuk asesmen BNSP
class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeder untuk data pengguna (Admin & Customer).
     */
    public function run(): void
    {
        // 1. Akun Administrator Utama
        User::updateOrCreate(
            ['email' => 'admin@ganbook.test'],
            [
                'name'     => 'Administrator GanBook',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'phone'    => '081234567890',
                'address'  => 'Kantor Pusat GanBook Sales, Jakarta Barat',
            ]
        );

        // 2. Akun Customer (Pelanggan E-Commerce)
        User::updateOrCreate(
            ['email' => 'customer@ganbook.test'],
            [
                'name'     => 'Budi Pratama (Customer)',
                'password' => Hash::make('password123'),
                'role'     => 'customer',
                'phone'    => '085712349988',
                'address'  => 'Jl. Anggrek Cendrawasih No. 42, Kebon Jeruk, Jakarta Barat',
            ]
        );
    }
}
