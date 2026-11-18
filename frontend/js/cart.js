// ==========================================
// APLIKASI GANBOOK SALES - TOKO ONLINE KOMIK
// File: cart.js
// Deskripsi: Manajemen antarmuka keranjang belanja komik (Cart Page)
// ==========================================

import { getCart, saveCart, formatRupiah, getCoverUrl } from './script.js';

// ==========================================
// BAGIAN 1: INISIALISASI HALAMAN KERANJANG
// Menghubungkan event listener tombol kosongkan keranjang dan lanjut ke checkout.
// ==========================================
export function initCartPage() {
    renderCartTable();

    document.getElementById('btnClearCart')?.addEventListener('click', () => {
        if (confirm('Kosongkan semua komik di keranjang belanja?')) {
            saveCart([]);
            renderCartTable();
        }
    });

    document.getElementById('btnProceedCheckout')?.addEventListener('click', () => {
        const cart = getCart();
        if (cart.length === 0) {
            alert('Keranjang belanja Anda masih kosong!');
            return;
        }

        // Pengunjung yang belum login harus login untuk melakukan checkout
        const token = localStorage.getItem('ganbook_token');
        if (!token) {
            alert('Silakan masuk (login) ke akun Anda terlebih dahulu untuk melakukan checkout pesanan.');
            window.location.href = 'login.html';
            return;
        }

        window.location.href = 'checkout.html';
    });
}

// ==========================================
// BAGIAN 2: MERENDER TABEL ITEM KERANJANG
// Menampilkan daftar item belanja, menghitung subtotal, dan memperbarui ringkasan.
// ==========================================
export function renderCartTable() {
    const tableBody = document.getElementById('cartItemsTableBody');
    const totalItemsEl = document.getElementById('cartSummaryTotalItems');
    const subtotalEl = document.getElementById('cartSummarySubtotal');
    const grandTotalEl = document.getElementById('cartSummaryGrandTotal');
    const checkoutBtn = document.getElementById('btnProceedCheckout');

    if (!tableBody) return;

    const cart = getCart();

    if (cart.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"></i>
                    <h3>Keranjang Belanja Kosong</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 20px;">Anda belum menambahkan komik apa pun ke keranjang.</p>
                    <a href="catalog.html" class="btn btn-primary"><i class="fas fa-book"></i> Jelajahi Katalog Komik</a>
                </td>
            </tr>
        `;
        if (totalItemsEl) totalItemsEl.textContent = '0 Buku';
        if (subtotalEl) subtotalEl.textContent = 'Rp 0';
        if (grandTotalEl) grandTotalEl.textContent = 'Rp 0';
        if (checkoutBtn) checkoutBtn.disabled = true;
        return;
    }

    if (checkoutBtn) checkoutBtn.disabled = false;

    let totalItems = 0;
    let grandTotal = 0;

    tableBody.innerHTML = cart.map((item, index) => {
        const itemSubtotal = item.price * item.quantity;
        totalItems += item.quantity;
        grandTotal += itemSubtotal;

        const coverUrl = getCoverUrl(item.cover_image);

        return `
            <tr>
                <td style="width: 80px;">
                    <img src="${coverUrl}" alt="${item.title}" style="width: 60px; height: 85px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </td>
                <td>
                    <span class="badge badge-info" style="font-size: 0.72rem;">${item.comic_type}</span>
                    <h4 style="margin: 4px 0 2px; font-size: 0.95rem;">${item.title}</h4>
                    <span class="text-muted" style="font-size: 0.8rem;">Stok tersedia: ${item.stock}</span>
                </td>
                <td><strong>${formatRupiah(item.price)}</strong></td>
                <td style="width: 140px;">
                    <div class="cart-qty-control">
                        <button type="button" class="btn-qty-minus" onclick="window.changeItemQty(${item.id}, -1)">-</button>
                        <input type="number" min="1" max="${item.stock}" value="${item.quantity}" class="form-control" style="text-align: center; width: 55px; padding: 4px;" onchange="window.setItemQty(${item.id}, this.value)">
                        <button type="button" class="btn-qty-plus" onclick="window.changeItemQty(${item.id}, 1)">+</button>
                    </div>
                </td>
                <td><strong>${formatRupiah(itemSubtotal)}</strong></td>
                <td style="width: 50px;">
                    <button class="btn btn-danger btn-sm" onclick="window.removeCartItem(${item.id})" title="Hapus komik ini">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');

    if (totalItemsEl) totalItemsEl.textContent = `${totalItems} Buku`;
    if (subtotalEl) subtotalEl.textContent = formatRupiah(grandTotal);
    if (grandTotalEl) grandTotalEl.textContent = formatRupiah(grandTotal);
}

// ==========================================
// BAGIAN 3: HANDLER GLOBAL ITEM KERANJANG
// Mengubah kuantitas item, memvalidasi batas stok, dan menghapus item dari keranjang.
// ==========================================
if (typeof window !== 'undefined') {
    window.changeItemQty = (id, delta) => {
        const cart = getCart();
        const item = cart.find(x => x.id === id);
        if (!item) return;

        const newQty = item.quantity + delta;
        if (newQty <= 0) {
            window.removeCartItem(id);
            return;
        }
        if (newQty > item.stock) {
            alert(`Jumlah melebihi batas stok tersedia (${item.stock})!`);
            return;
        }

        item.quantity = newQty;
        saveCart(cart);
        renderCartTable();
    };

    window.setItemQty = (id, val) => {
        const qty = parseInt(val, 10);
        const cart = getCart();
        const item = cart.find(x => x.id === id);
        if (!item) return;

        if (isNaN(qty) || qty <= 0) {
            window.removeCartItem(id);
            return;
        }
        if (qty > item.stock) {
            alert(`Jumlah melebihi batas stok (${item.stock})!`);
            item.quantity = item.stock;
        } else {
            item.quantity = qty;
        }

        saveCart(cart);
        renderCartTable();
    };

    window.removeCartItem = (id) => {
        let cart = getCart();
        cart = cart.filter(x => x.id !== id);
        saveCart(cart);
        renderCartTable();
    };
}
