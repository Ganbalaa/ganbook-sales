// ==========================================
// APLIKASI GANBOOK SALES - TOKO ONLINE KOMIK
// File: script.js
// Deskripsi: Konfigurasi utama, API client, utilitas, dan navbar
// ==========================================

// URL dasar API Laravel backend (menggunakan env Vite jika tersedia)
export const API_BASE_URL = (typeof import.meta !== 'undefined' && import.meta.env && import.meta.env.VITE_API_BASE_URL)
    ? import.meta.env.VITE_API_BASE_URL
    : 'http://127.0.0.1:8000/api';

// Pastikan variabel global tersedia untuk skrip non-modul jika diperlukan
if (typeof window !== 'undefined') {
    window.API_BASE_URL = API_BASE_URL;
}

// ==========================================
// BAGIAN 1: HELPER AUTENTIKASI & TOKEN
// Mengelola penyimpanan token Sanctum, data sesi pengguna di localStorage, dan verifikasi Role Guard.
// ==========================================

export function getAuthToken() {
    return localStorage.getItem('ganbook_token');
}

export function getAuthUser() {
    const userStr = localStorage.getItem('ganbook_user');
    try {
        return userStr ? JSON.parse(userStr) : null;
    } catch (e) {
        return null;
    }
}

export function setAuthSession(token, user) {
    localStorage.setItem('ganbook_token', token);
    localStorage.setItem('ganbook_user', JSON.stringify(user));
}

export function clearAuthSession() {
    localStorage.removeItem('ganbook_token');
    localStorage.removeItem('ganbook_user');
}

/**
 * Memeriksa hak akses halaman berdasarkan peran pengguna (Role Guard).
 * @param {Array<string>} allowedRoles Daftar peran yang diizinkan (contoh: ['admin'])
 * @param {boolean} redirectIfFail Alihkan ke login jika gagal
 */
export function requireRole(allowedRoles = [], redirectIfFail = true) {
    const token = getAuthToken();
    const user = getAuthUser();

    if (!token || !user) {
        if (redirectIfFail) {
            alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
            window.location.href = 'login.html';
        }
        return false;
    }

    if (allowedRoles.length > 0 && !allowedRoles.includes(user.role)) {
        if (redirectIfFail) {
            alert(`Akses ditolak. Peran Anda (${user.role.toUpperCase()}) tidak diizinkan membuka halaman ini.`);
            if (user.role === 'admin') window.location.href = 'dashboard.html';
            else window.location.href = 'index.html';
        }
        return false;
    }

    return true;
}

// ==========================================
// BAGIAN 2: HTTP REQUEST API WRAPPER
// Mengirim permintaan AJAX/Fetch ke backend Laravel dengan header otorisasi dan penanganan error.
// ==========================================

export async function apiRequest(endpoint, method = 'GET', data = null) {
    const token = getAuthToken();
    const headers = {
        'Accept': 'application/json'
    };

    const isFormData = typeof FormData !== 'undefined' && data instanceof FormData;

    // Jika data berupa JSON biasa, set header Content-Type.
    // Jika FormData (file upload), biarkan kosong agar browser otomatis mengatur boundary multipart/form-data.
    if (!isFormData) {
        headers['Content-Type'] = 'application/json';
    }

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const options = {
        method: method,
        headers: headers
    };

    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
        options.body = isFormData ? data : JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
        const result = await response.json();

        // Tangani sesi kedaluwarsa (401 Unauthorized)
        if (response.status === 401 && !endpoint.includes('/login') && !endpoint.includes('/register')) {
            clearAuthSession();
            alert('Sesi login telah berakhir. Silakan masuk kembali.');
            window.location.href = 'login.html';
            return { ok: false, status: 401, data: null };
        }

        return {
            ok: response.ok,
            status: response.status,
            data: result
        };
    } catch (error) {
        console.error('Error komunikasi API:', error);
        return {
            ok: false,
            status: 500,
            data: { message: 'Gagal terhubung ke backend Laravel (127.0.0.1:8000). Pastikan "php artisan serve" aktif.' }
        };
    }
}

// ==========================================
// BAGIAN 3: UTILITAS FORMATTER & RESOLUSI COVER
// Memformat nominal Rupiah, tanggal transaksi, dan URL publik gambar cover komik.
// ==========================================

export function formatRupiah(number) {
    const num = Number(number) || 0;
    return 'Rp ' + num.toLocaleString('id-ID');
}

export function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Menghasilkan URL publik untuk cover komik yang valid.
 * Menangani URL eksternal, path storage publik backend (storage/covers/...),
 * maupun file aset statis lokal (assets/covers/...).
 */
export function getCoverUrl(coverPath) {
    if (!coverPath) return 'assets/covers/one-piece.svg';
    if (coverPath.startsWith('http://') || coverPath.startsWith('https://')) {
        return coverPath;
    }
    if (coverPath.startsWith('storage/') || coverPath.startsWith('/storage/')) {
        const cleanPath = coverPath.startsWith('/') ? coverPath.substring(1) : coverPath;
        const backendOrigin = API_BASE_URL.replace(/\/api\/?$/, '');
        return `${backendOrigin}/${cleanPath}`;
    }
    if (coverPath.startsWith('covers/')) {
        const backendOrigin = API_BASE_URL.replace(/\/api\/?$/, '');
        return `${backendOrigin}/storage/${coverPath}`;
    }
    return coverPath;
}

if (typeof window !== 'undefined') {
    window.getCoverUrl = getCoverUrl;
}

// ==========================================
// BAGIAN 4: SISTEM KERANJANG BELANJA (CART)
// Mengelola penyimpanan keranjang pada browser, validasi batas stok, dan lencana item belanja.
// ==========================================

export function getCart() {
    try {
        return JSON.parse(localStorage.getItem('ganbook_cart')) || [];
    } catch (e) {
        return [];
    }
}

export function saveCart(cart) {
    localStorage.setItem('ganbook_cart', JSON.stringify(cart));
    updateCartCountBadge();
}

export function addToCart(comic, quantity = 1) {
    const cart = getCart();
    const existing = cart.find(item => item.id === comic.id);

    const qtyToAdd = Number(quantity) || 1;
    const currentQty = existing ? existing.quantity : 0;
    const newQty = currentQty + qtyToAdd;

    if (newQty > comic.stock) {
        alert(`Maaf, stok komik '${comic.title}' hanya tersisa ${comic.stock} unit.`);
        return false;
    }

    if (existing) {
        existing.quantity = newQty;
    } else {
        cart.push({
            id: comic.id,
            title: comic.title,
            price: Number(comic.price),
            cover_image: comic.cover_image,
            comic_type: comic.comic_type,
            stock: comic.stock,
            quantity: qtyToAdd
        });
    }

    saveCart(cart);
    alert(`"${comic.title}" berhasil ditambahkan ke keranjang belanja!`);
    return true;
}

export function updateCartCountBadge() {
    const badge = document.getElementById('navCartCount');
    if (!badge) return;
    const cart = getCart();
    const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
    badge.textContent = totalItems;
    badge.style.display = totalItems > 0 ? 'inline-block' : 'none';
}

// ==========================================
// BAGIAN 5: RENDER NAVBAR PUBLIK & USER
// Membangun bilah navigasi dinamis, lencana peran pengguna, dan fungsi keluar akun (logout).
// ==========================================

export function initStoreNavbar() {
    updateCartCountBadge();
    const user = getAuthUser();
    const userNavArea = document.getElementById('storeUserNav');
    if (!userNavArea) return;

    if (user) {
        // Tampilkan profil dan tautan sesuai peran (Role)
        let dashboardLink = '';
        if (user.role === 'admin') {
            dashboardLink = `<a href="dashboard.html" class="nav-btn btn-admin"><i class="fas fa-chart-line"></i> Dashboard Admin</a>`;
        }

        userNavArea.innerHTML = `
            <div class="user-dropdown">
                <button class="nav-user-btn">
                    <i class="fas fa-user-circle"></i>
                    <span>${user.name.split(' ')[0]}</span>
                    <span class="user-role-pill">${user.role.toUpperCase()}</span>
                </button>
                <div class="dropdown-content">
                    ${dashboardLink}
                    <a href="orders.html"><i class="fas fa-receipt"></i> Riwayat Pesanan</a>
                    <hr>
                    <a href="#" id="btnStoreLogout" style="color: var(--danger-color);"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                </div>
            </div>
        `;

        document.getElementById('btnStoreLogout')?.addEventListener('click', async (e) => {
            e.preventDefault();
            if (confirm('Keluar dari akun Anda?')) {
                try { await apiRequest('/logout', 'POST'); } catch (err) {}
                clearAuthSession();
                window.location.reload();
            }
        });
    } else {
        // Pengunjung belum login
        userNavArea.innerHTML = `
            <a href="login.html" class="btn btn-outline btn-sm"><i class="fas fa-sign-in-alt"></i> Masuk</a>
            <a href="register.html" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Daftar</a>
        `;
    }
}

// Jalankan otomatis pada saat DOM siap jika file di-import langsung
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        initStoreNavbar();
    });
}
