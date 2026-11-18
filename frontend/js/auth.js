// ==========================================
// APLIKASI GANBOOK SALES - TOKO ONLINE KOMIK
// File: auth.js
// Deskripsi: Penanganan form login dan registrasi pelanggan
// ==========================================

import { apiRequest, setAuthSession, getAuthUser } from './script.js';

// ==========================================
// BAGIAN 1: FORMULIR LOGIN (ADMIN & CUSTOMER)
// Menangani autentikasi masuk, penyimpanan token Sanctum, dan pengalihan halaman.
// ==========================================
export function initLoginForm() {
    const form = document.getElementById('loginForm');
    const alertBox = document.getElementById('loginAlert');
    const submitBtn = document.getElementById('btnSubmitLogin');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        if (!email || !password) {
            showAlert(alertBox, 'Email dan password wajib diisi!', 'danger');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa...';
        hideAlert(alertBox);

        const res = await apiRequest('/login', 'POST', { email, password });

        if (res.ok && res.data.success) {
            setAuthSession(res.data.token, res.data.user);
            showAlert(alertBox, 'Login berhasil! Mengalihkan ke halaman tujuan...', 'success');

            setTimeout(() => {
                const user = res.data.user;
                if (user.role === 'admin') {
                    window.location.href = 'dashboard.html';
                } else {
                    // Customer diarahkan ke beranda
                    window.location.href = 'index.html';
                }
            }, 600);
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Masuk Sekarang';
            showAlert(alertBox, res.data?.message || 'Email atau kata sandi tidak sesuai.', 'danger');
        }
    });
}

// ==========================================
// BAGIAN 2: FORMULIR REGISTRASI PELANGGAN
// Mendaftarkan akun customer baru dan langsung mengaktifkan sesi login.
// ==========================================
export function initRegisterForm() {
    const form = document.getElementById('registerForm');
    const alertBox = document.getElementById('registerAlert');
    const submitBtn = document.getElementById('btnSubmitRegister');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('regName').value.trim();
        const email = document.getElementById('regEmail').value.trim();
        const phone = document.getElementById('regPhone').value.trim();
        const address = document.getElementById('regAddress').value.trim();
        const password = document.getElementById('regPassword').value;
        const password_confirmation = document.getElementById('regPasswordConfirm').value;

        if (password !== password_confirmation) {
            showAlert(alertBox, 'Konfirmasi kata sandi tidak cocok!', 'danger');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendaftarkan Akun...';
        hideAlert(alertBox);

        const payload = {
            name,
            email,
            phone,
            address,
            password,
            password_confirmation
        };

        const res = await apiRequest('/register', 'POST', payload);

        if (res.ok && res.data.success) {
            setAuthSession(res.data.token, res.data.user);
            showAlert(alertBox, 'Pendaftaran berhasil! Mengalihkan ke Beranda...', 'success');
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 800);
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Daftar Akun Pelanggan';
            const errorMsg = res.data?.message || 'Pendaftaran gagal. Periksa kembali formulir Anda.';
            showAlert(alertBox, errorMsg, 'danger');
        }
    });
}

// ==========================================
// BAGIAN 3: BANTUAN NOTIFIKASI FORMULIR
// Menampilkan dan menyembunyikan pesan status sukses atau galat.
// ==========================================
function showAlert(element, message, type = 'danger') {
    if (!element) return;
    element.className = `alert alert-${type}`;
    element.textContent = message;
    element.style.display = 'block';
}

function hideAlert(element) {
    if (!element) return;
    element.style.display = 'none';
}
