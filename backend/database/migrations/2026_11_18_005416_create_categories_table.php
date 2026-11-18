<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel categories.
     */
    public function up(): void
    {
        // ==========================================
        // TABEL KATEGORI & GENRE KOMIK (CATEGORIES)
        // ==========================================
        // Menyimpan klasifikasi tipe komik (Manga, Manhwa, Manhua) dan genre
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();                    // Nama kategori (contoh: Manga, Manhwa, Manhua, Action, Fantasy)
            $table->string('type')->default('genre');            // Jenis kategori: origin (asal komik) atau genre
            $table->text('description')->nullable();             // Deskripsi atau catatan kategori
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
