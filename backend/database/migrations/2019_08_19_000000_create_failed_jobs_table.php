<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ==========================================
// BAGIAN: MIGRATION LOG TUGAS ANTREAN GAGAL (FAILED JOBS)
// Membuat tabel untuk mencatat riwayat job antrean (Queue) yang mengalami kegagalan eksekusi.
// ==========================================
return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel failed_jobs.
     */
    public function up(): void
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Membalikkan migration (menghapus tabel dari database).
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
    }
};
