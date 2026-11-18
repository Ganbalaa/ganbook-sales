<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

// ==========================================
// BAGIAN: FACTORY DATA PENGGUNA (USER FACTORY)
// Menghasilkan data tiruan pengguna (dummy user) untuk kebutuhan testing otomatis.
// ==========================================
class UserFactory extends Factory
{
    /**
     * Menentukan nilai atribut default untuk model User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password123
            'remember_token' => Str::random(10),
            'role' => 'customer',
        ];
    }

    /**
     * Menandai bahwa alamat email pengguna belum diverifikasi.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
