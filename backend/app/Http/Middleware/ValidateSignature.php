<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

// ==========================================
// BAGIAN: VALIDASI TANDA TANGAN URL (SIGNED URL)
// Memverifikasi keaslian URL bertanda tangan untuk mencegah pemalsuan tautan.
// ==========================================
class ValidateSignature extends Middleware
{
    /**
     * Parameter query string yang diabaikan saat validasi tanda tangan URL.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];
}
