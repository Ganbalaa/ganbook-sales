<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel books (produk komik).
     */
    public function up(): void
    {
        // ==========================================
        // TABEL PRODUK KOMIK (BOOKS)
        // ==========================================
        // Menyimpan data katalog komik (Manga, Manhwa, Manhua), stok, dan harga
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')                     // Foreign key ke tabel categories
                  ->constrained('categories')
                  ->onDelete('cascade');
            $table->string('title');                             // Judul komik (contoh: One Piece, Solo Leveling)
            $table->string('author');                            // Nama mangaka / komikus / author
            $table->string('publisher');                         // Nama penerbit komik (contoh: Elex Media, M&C!)
            $table->string('comic_type')->default('Manga');      // Tipe komik: Manga, Manhwa, Manhua
            $table->string('genre')->default('Action');          // Genre komik: Action, Fantasy, Adventure, dll
            $table->integer('year');                             // Tahun rilis komik
            $table->decimal('price', 12, 2);                     // Harga jual komik per volume (Rupiah)
            $table->integer('stock')->default(0);                // Jumlah stok fisik komik yang tersedia
            $table->string('cover_image')->nullable();           // Path atau URL cover resmi komik
            $table->text('description')->nullable();             // Sinopsis / deskripsi cerita komik
            $table->boolean('is_popular')->default(false);       // Penanda komik terpopuler di beranda
            $table->boolean('is_recommended')->default(false);   // Penanda rekomendasi pilihan toko
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
