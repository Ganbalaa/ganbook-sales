<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ==========================================
// BAGIAN: MIGRATION TOKEN RESET KATA SANDI
// Membuat tabel penyimpanan token sementara untuk fitur lupa kata sandi.
// ==========================================
return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel password_reset_tokens.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel dari database).
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
