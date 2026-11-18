<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

// ==========================================
// BAGIAN: KEAMANAN HOST TERPERCAYA (TRUST HOSTS)
// Mencegah serangan Host Header Injection dengan membatasi host yang valid.
// ==========================================
class TrustHosts extends Middleware
{
    /**
     * Mengambil pola nama host domain yang dipercaya oleh aplikasi.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
