<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// ==========================================
// BAGIAN: CONSOLE KERNEL ARTISAN
// Mendaftarkan perintah CLI kustom dan penjadwalan tugas otomatis (scheduler).
// ==========================================
class Kernel extends ConsoleKernel
{
    /**
     * Mendefinisikan jadwal tugas otomatis aplikasi (Cron Jobs).
     */
    protected function schedule(Schedule $schedule): void
    {
        // Contoh: $schedule->command('inspire')->hourly();
    }

    /**
     * Mendaftarkan perintah Artisan kustom untuk aplikasi.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
