// ==========================================
// APLIKASI GANBOOK SALES - TOKO ONLINE KOMIK
// File: products.js
// Deskripsi: Menampilkan katalog komik, filter pencarian, dan modal detail komik
// ==========================================

import { apiRequest, formatRupiah, addToCart, getCoverUrl } from './script.js';

let currentComics = [];

// ==========================================
// BAGIAN 1: INISIALISASI BERANDA (HOMEPAGE)
// Memuat komik terbaru, terpopuler, dan rekomendasi dari API Laravel.
// ==========================================
export async function initHomePage() {
    // 1. Muat Komik Terbaru (Limit 4)
    loadSectionComics('/books?limit=4&sort=latest', 'latestComicsContainer');

    // 2. Muat Komik Terpopuler (is_popular=1)
    loadSectionComics('/books?is_popular=1&limit=4', 'popularComicsContainer');

    // 3. Muat Rekomendasi Toko (is_recommended=1)
    loadSectionComics('/books?is_recommended=1&limit=4', 'recommendedComicsContainer');
}

async function loadSectionComics(endpoint, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Memuat koleksi komik...</div>';

    const res = await apiRequest(endpoint);
    if (res.ok && res.data.success) {
        const comics = res.data.data;
        if (comics.length === 0) {
            container.innerHTML = '<p class="text-muted" style="grid-column: 1/-1; text-align: center;">Belum ada komik pada kategori ini.</p>';
            return;
        }

        // Simpan ke array global untuk modal
        comics.forEach(c => {
            if (!currentComics.find(x => x.id === c.id)) currentComics.push(c);
        });

        container.innerHTML = comics.map(c => renderComicCard(c)).join('');
        attachCardEventListeners();
    } else {
        container.innerHTML = '<p class="text-danger" style="grid-column: 1/-1; text-align: center;">Gagal memuat komik.</p>';
    }
}

// ==========================================
// BAGIAN 2: INISIALISASI HALAMAN KATALOG
// Memuat filter kategori, opsi urutan, serta menangani pencarian komik dengan debounce.
// ==========================================
export async function initCatalogPage() {
    await loadCategoriesFilter();
    await fetchCatalogComics();

    // Event listeners pencarian & filter
    document.getElementById('catalogSearch')?.addEventListener('input', debounce(fetchCatalogComics, 300));
    document.getElementById('catalogTypeFilter')?.addEventListener('change', fetchCatalogComics);
    document.getElementById('catalogCategoryFilter')?.addEventListener('change', fetchCatalogComics);
    document.getElementById('catalogSort')?.addEventListener('change', fetchCatalogComics);

    // Cek query string URL (misal: ?type=Manga)
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    if (typeParam) {
        const select = document.getElementById('catalogTypeFilter');
        if (select) {
            select.value = typeParam;
            fetchCatalogComics();
        }
    }
}

async function loadCategoriesFilter() {
    const select = document.getElementById('catalogCategoryFilter');
    if (!select) return;

    const res = await apiRequest('/categories');
    if (res.ok && res.data.success) {
        const categories = res.data.data;
        select.innerHTML = '<option value="">Semua Kategori & Genre</option>' +
            categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('');
    }
}

export async function fetchCatalogComics() {
    const container = document.getElementById('catalogGrid');
    const resultCount = document.getElementById('catalogResultCount');
    if (!container) return;

    container.innerHTML = '<div class="loading-spinner" style="grid-column: 1/-1;"><i class="fas fa-spinner fa-spin"></i> Memuat katalog komik...</div>';

    const search = document.getElementById('catalogSearch')?.value.trim() || '';
    const comicType = document.getElementById('catalogTypeFilter')?.value || '';
    const categoryId = document.getElementById('catalogCategoryFilter')?.value || '';
    const sort = document.getElementById('catalogSort')?.value || 'latest';

    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (comicType) params.append('comic_type', comicType);
    if (categoryId) params.append('category_id', categoryId);
    if (sort) params.append('sort', sort);

    const res = await apiRequest(`/books?${params.toString()}`);
    if (res.ok && res.data.success) {
        currentComics = res.data.data;
        if (resultCount) resultCount.textContent = `Menampilkan ${currentComics.length} komik`;

        if (currentComics.length === 0) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <i class="fas fa-book-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"></i>
                    <h3>Komik tidak ditemukan</h3>
                    <p style="color: var(--text-secondary);">Coba ubah kata kunci pencarian atau ganti filter tipe komik.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = currentComics.map(c => renderComicCard(c)).join('');
        attachCardEventListeners();
    }
}

// ==========================================
// BAGIAN 3: RENDER KARTU KOMIK (PRODUCT CARD)
// Membangun elemen kartu komik fisik lengkap dengan lencana Manga/Manhwa/Manhua, harga, dan stok.
// ==========================================
export function renderComicCard(comic) {
    const typeBadgeClass = comic.comic_type === 'Manga' ? 'badge-manga'
                         : (comic.comic_type === 'Manhwa' ? 'badge-manhwa' : 'badge-manhua');

    const stockBadge = comic.stock > 0
        ? `<span class="stock-badge in-stock"><i class="fas fa-check"></i> Stok: ${comic.stock}</span>`
        : `<span class="stock-badge out-of-stock"><i class="fas fa-times"></i> Habis</span>`;

    const coverUrl = getCoverUrl(comic.cover_url || comic.cover_image);

    return `
        <div class="comic-card">
            <div class="comic-cover-wrapper">
                <img src="${coverUrl}" alt="${comic.title}" class="comic-cover" loading="lazy">
                <span class="comic-type-badge ${typeBadgeClass}">${comic.comic_type}</span>
                ${comic.is_popular ? '<span class="popular-ribbon"><i class="fas fa-fire"></i> Populer</span>' : ''}
            </div>
            <div class="comic-card-body">
                <div class="comic-genre">${comic.genre || 'Action, Fantasy'}</div>
                <h4 class="comic-title" title="${comic.title}">${comic.title}</h4>
                <div class="comic-meta">
                    <span><i class="fas fa-pen-nib"></i> ${comic.author}</span>
                </div>
                <div class="comic-price-row">
                    <div class="comic-price">${formatRupiah(comic.price)}</div>
                    ${stockBadge}
                </div>
                <div class="comic-actions">
                    <button class="btn btn-secondary btn-sm btn-detail" data-id="${comic.id}">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    <button class="btn btn-primary btn-sm btn-add-cart" data-id="${comic.id}" ${comic.stock <= 0 ? 'disabled' : ''}>
                        <i class="fas fa-cart-plus"></i> ${comic.stock > 0 ? '+ Keranjang' : 'Habis'}
                    </button>
                </div>
            </div>
        </div>
    `;
}

function attachCardEventListeners() {
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = Number(e.currentTarget.getAttribute('data-id'));
            showComicDetailModal(id);
        });
    });

    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = Number(e.currentTarget.getAttribute('data-id'));
            const comic = currentComics.find(c => c.id === id);
            if (comic) addToCart(comic, 1);
        });
    });
}

// ==========================================
// BAGIAN 4: MODAL DETAIL KOMIK (POPUP)
// Menampilkan jendela rincian spesifikasi komik, sinopsis lengkap, dan pemilihan kuantitas beli.
// ==========================================
export function showComicDetailModal(comicId) {
    const comic = currentComics.find(c => c.id === comicId);
    if (!comic) return;

    let modal = document.getElementById('comicDetailModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'comicDetailModal';
        modal.className = 'modal-overlay';
        document.body.appendChild(modal);
    }

    const coverUrl = getCoverUrl(comic.cover_url || comic.cover_image);

    modal.innerHTML = `
        <div class="modal-content modal-detail-layout">
            <div class="modal-header">
                <h3>Detail Komik: ${comic.title}</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('comicDetailModal').classList.remove('active')">&times;</button>
            </div>
            <div class="modal-body detail-grid">
                <div class="detail-cover-area">
                    <img src="${coverUrl}" alt="${comic.title}" class="detail-cover-img">
                </div>
                <div class="detail-info-area">
                    <span class="badge badge-info" style="font-size: 0.85rem;">Tipe: ${comic.comic_type}</span>
                    <h2 style="margin: 8px 0; font-size: 1.5rem; color: var(--text-primary);">${comic.title}</h2>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary-color); margin-bottom: 15px;">
                        ${formatRupiah(comic.price)}
                    </div>

                    <div class="detail-specs">
                        <div><strong>Penulis/Komikus:</strong> ${comic.author}</div>
                        <div><strong>Penerbit:</strong> ${comic.publisher}</div>
                        <div><strong>Genre:</strong> ${comic.genre}</div>
                        <div><strong>Tahun Rilis:</strong> ${comic.year}</div>
                        <div><strong>Sisa Stok:</strong> <span class="badge ${comic.stock > 0 ? 'badge-success' : 'badge-danger'}">${comic.stock} unit</span></div>
                    </div>

                    <h4 style="margin: 15px 0 6px; font-size: 0.95rem;">Sinopsis / Deskripsi:</h4>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6;">
                        ${comic.description || 'Kisah petualangan komik seru dengan visual berkualitas tinggi.'}
                    </p>

                    <div style="display: flex; gap: 12px; align-items: center; margin-top: 25px;">
                        <input type="number" id="detailQtyInput" min="1" max="${comic.stock}" value="1" class="form-control" style="width: 80px;" ${comic.stock <= 0 ? 'disabled' : ''}>
                        <button type="button" id="btnDetailAddToCart" class="btn btn-primary" style="flex: 1;" ${comic.stock <= 0 ? 'disabled' : ''}>
                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    modal.classList.add('active');

    document.getElementById('btnDetailAddToCart')?.addEventListener('click', () => {
        const qty = Number(document.getElementById('detailQtyInput')?.value) || 1;
        if (addToCart(comic, qty)) {
            modal.classList.remove('active');
        }
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.remove('active');
    });
}

// ==========================================
// BAGIAN 5: FUNGSI BANTUAN DEBOUNCE
// Menunda eksekusi pencarian input teks agar tidak membebani pemanggilan API backend.
// ==========================================
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
