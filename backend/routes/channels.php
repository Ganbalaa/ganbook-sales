<?php

use Illuminate\Support\Facades\Broadcast;

// ==========================================
// BAGIAN: RUTE KANAL SIARAN BROADCAST
// Mengatur otorisasi pengguna pada saluran broadcast real-time (WebSockets).
// ==========================================

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
