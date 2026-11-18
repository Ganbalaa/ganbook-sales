<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel users.
     */
    public function up(): void
    {
        // ==========================================
        // TABEL PENGGUNA (USERS)
        // ==========================================
        // Menyimpan data administrator dan customer e-commerce
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                      // Nama lengkap pengguna
            $table->string('email')->unique();                          // Alamat email unik untuk login
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');                                  // Password yang di-hash
            $table->string('role')->default('customer');                // Peran: admin, customer
            $table->string('phone')->nullable();                        // Nomor telepon / WhatsApp
            $table->text('address')->nullable();                         // Alamat pengiriman pelanggan
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
