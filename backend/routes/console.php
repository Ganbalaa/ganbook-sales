<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// ==========================================
// BAGIAN: RUTE PERINTAH TERMINAL (ARTISAN CONSOLE)
// Menentukan perintah berbasis Closure untuk dieksekusi melalui terminal CMD/PowerShell.
// ==========================================

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Menampilkan kutipan motivasi programmer');
