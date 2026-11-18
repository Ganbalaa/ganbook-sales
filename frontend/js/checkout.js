// ==========================================
// APLIKASI GANBOOK SALES - TOKO ONLINE KOMIK
// File: checkout.js
// Deskripsi: Pemrosesan formulir checkout, metode pembayaran QRIS/COD, dan pengiriman pesanan
// ==========================================

import { getCart, saveCart, getAuthUser, apiRequest, formatRupiah } from './script.js';

// ==========================================
// BAGIAN 1: INISIALISASI HALAMAN CHECKOUT
// Memeriksa autentikasi login pengguna, memeriksa isi keranjang, dan mendaftarkan event listener.
// ==========================================
export function initCheckoutPage() {
    // Pengunjung wajib login sebelum dapat mengakses dan memproses checkout
    const user = getAuthUser();
    if (!user) {
        alert('Silakan login terlebih dahulu untuk melakukan checkout pesanan.');
        window.location.href = 'login.html';
        return;
    }

    const cart = getCart();
    if (cart.length === 0) {
        alert('Keranjang belanja Anda kosong. Silakan pilih komik terlebih dahulu.');
        window.location.href = 'catalog.html';
        return;
    }

    renderCheckoutSummary();
    prefillCustomerData();

    // Event listener pergantian metode pembayaran (QRIS / COD)
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            const method = e.target.value;
            togglePaymentInfo(method);
        });
    });

    // Submit checkout
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', handleCheckoutSubmit);
    }
}

// ==========================================
// BAGIAN 2: MENGISI OTOMATIS DATA PELANGGAN
// Membaca identitas user yang login untuk melengkapi nama, telepon, dan alamat kirim.
// ==========================================
function prefillCustomerData() {
    const user = getAuthUser();
    if (!user) return;

    const nameInput = document.getElementById('checkoutName');
    const phoneInput = document.getElementById('checkoutPhone');
    const addressInput = document.getElementById('checkoutAddress');

    if (nameInput && !nameInput.value) nameInput.value = user.name || '';
    if (phoneInput && !phoneInput.value) phoneInput.value = user.phone || '';
    if (addressInput && !addressInput.value) addressInput.value = user.address || '';
}

// ==========================================
// BAGIAN 3: RINGKASAN PESANAN & TAMPILAN PEMBAYARAN
// Merender daftar item pesanan dan beralih antara panduan bayar QRIS atau COD.
// ==========================================
function renderCheckoutSummary() {
    const listContainer = document.getElementById('checkoutItemsList');
    const totalEl = document.getElementById('checkoutTotalAmount');
    if (!listContainer) return;

    const cart = getCart();
    let total = 0;

    listContainer.innerHTML = cart.map(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        return `
            <div class="checkout-item-row">
                <div>
                    <strong>${item.title}</strong>
                    <div class="text-muted" style="font-size: 0.8rem;">${item.quantity} x ${formatRupiah(item.price)}</div>
                </div>
                <div><strong>${formatRupiah(subtotal)}</strong></div>
            </div>
        `;
    }).join('');

    if (totalEl) totalEl.textContent = formatRupiah(total);
}

function togglePaymentInfo(method) {
    const qrisBox = document.getElementById('qrisPaymentBox');
    const codBox = document.getElementById('codPaymentBox');

    if (method === 'qris') {
        if (qrisBox) qrisBox.style.display = 'block';
        if (codBox) codBox.style.display = 'none';
    } else {
        if (qrisBox) qrisBox.style.display = 'none';
        if (codBox) codBox.style.display = 'block';
    }
}

// ==========================================
// BAGIAN 4: PEMROSESAN SUBMIT PESANAN
// Mengirim data checkout dan daftar item ke endpoint POST /sales di backend Laravel.
// ==========================================
async function handleCheckoutSubmit(e) {
    e.preventDefault();

    const cart = getCart();
    if (cart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }

    const name = document.getElementById('checkoutName').value.trim();
    const phone = document.getElementById('checkoutPhone').value.trim();
    const address = document.getElementById('checkoutAddress').value.trim();
    const notes = document.getElementById('checkoutNotes')?.value.trim() || '';

    const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'cod';

    const submitBtn = document.getElementById('btnSubmitOrder');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Pesanan...';

    const payload = {
        customer_name: name,
        customer_phone: phone,
        customer_address: address,
        payment_method: selectedPayment,
        notes: notes,
        items: cart.map(item => ({
            book_id: item.id,
            quantity: item.quantity
        }))
    };

    const res = await apiRequest('/sales', 'POST', payload);

    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="fas fa-lock"></i> Konfirmasi & Buat Pesanan';

    if (res.ok && res.data.success) {
        const order = res.data.data;
        // Kosongkan keranjang belanja
        saveCart([]);

        // Tampilkan Modal Berhasil
        showOrderSuccessModal(order);
    } else {
        alert(res.data?.message || 'Gagal memproses pesanan komik.');
    }
}

// ==========================================
// BAGIAN 5: MODAL KONFIRMASI SUKSES PESANAN
// Menampilkan nomor invoice, total tagihan, status lunas QRIS/COD, dan tautan riwayat.
// ==========================================
function showOrderSuccessModal(order) {
    let modal = document.getElementById('orderSuccessModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'orderSuccessModal';
        modal.className = 'modal-overlay';
        document.body.appendChild(modal);
    }

    const isQris = order.payment_method === 'qris';

    modal.innerHTML = `
        <div class="modal-content" style="max-width: 480px; text-align: center;">
            <div class="modal-header" style="justify-content: center; border: none; padding-bottom: 0;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background-color: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto;">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div class="modal-body" style="padding-top: 10px;">
                <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 6px;">Pesanan Komik Berhasil!</h2>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Terima kasih atas pesanan Anda di GanBook Sales.</p>

                <div class="order-invoice-badge" style="margin: 15px 0; padding: 12px; background: #eff6ff; border-radius: 8px; border: 1px dashed #93c5fd;">
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">No. Faktur / Invoice:</div>
                    <div style="font-size: 1.2rem; font-weight: 800; color: var(--primary-color);">#${order.invoice_number}</div>
                    <div style="font-size: 1rem; font-weight: 700; margin-top: 4px;">Total: ${formatRupiah(order.total_price)}</div>
                </div>

                ${isQris ? `
                    <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px; font-size: 0.82rem; color: #92400e; margin-bottom: 15px;">
                        <i class="fas fa-qrcode"></i> <strong>Pembayaran QRIS:</strong> Pembayaran Anda telah dikonfirmasi lunas oleh sistem.
                    </div>
                ` : `
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px; font-size: 0.82rem; color: #166534; margin-bottom: 15px;">
                        <i class="fas fa-truck"></i> <strong>Metode COD:</strong> Siapkan uang tunai sebesar <strong>${formatRupiah(order.total_price)}</strong> saat kurir mengantar pesanan.
                    </div>
                `}

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="orders.html" class="btn btn-primary" style="flex: 1;"><i class="fas fa-receipt"></i> Lihat Riwayat Pesanan</a>
                    <a href="catalog.html" class="btn btn-secondary" style="flex: 1;"><i class="fas fa-book"></i> Belanja Lagi</a>
                </div>
            </div>
        </div>
    `;

    modal.classList.add('active');
}
