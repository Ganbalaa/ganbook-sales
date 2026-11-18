import{a as m,g as y,f,h as b}from"./script-Cjw_6am7.js";let c=[];async function T(){d("/books?limit=4&sort=latest","latestComicsContainer"),d("/books?is_popular=1&limit=4","popularComicsContainer"),d("/books?is_recommended=1&limit=4","recommendedComicsContainer")}async function d(e,t){const a=document.getElementById(t);if(!a)return;a.innerHTML='<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Memuat koleksi komik...</div>';const i=await m(e);if(i.ok&&i.data.success){const s=i.data.data;if(s.length===0){a.innerHTML='<p class="text-muted" style="grid-column: 1/-1; text-align: center;">Belum ada komik pada kategori ini.</p>';return}s.forEach(n=>{c.find(o=>o.id===n.id)||c.push(n)}),a.innerHTML=s.map(n=>k(n)).join(""),h()}else a.innerHTML='<p class="text-danger" style="grid-column: 1/-1; text-align: center;">Gagal memuat komik.</p>'}async function B(){var a,i,s,n;await E(),await r(),(a=document.getElementById("catalogSearch"))==null||a.addEventListener("input",L(r,300)),(i=document.getElementById("catalogTypeFilter"))==null||i.addEventListener("change",r),(s=document.getElementById("catalogCategoryFilter"))==null||s.addEventListener("change",r),(n=document.getElementById("catalogSort"))==null||n.addEventListener("change",r);const t=new URLSearchParams(window.location.search).get("type");if(t){const o=document.getElementById("catalogTypeFilter");o&&(o.value=t,r())}}async function E(){const e=document.getElementById("catalogCategoryFilter");if(!e)return;const t=await m("/categories");if(t.ok&&t.data.success){const a=t.data.data;e.innerHTML='<option value="">Semua Kategori & Genre</option>'+a.map(i=>`<option value="${i.id}">${i.name}</option>`).join("")}}async function r(){var u,g,p,v;const e=document.getElementById("catalogGrid"),t=document.getElementById("catalogResultCount");if(!e)return;e.innerHTML='<div class="loading-spinner" style="grid-column: 1/-1;"><i class="fas fa-spinner fa-spin"></i> Memuat katalog komik...</div>';const a=((u=document.getElementById("catalogSearch"))==null?void 0:u.value.trim())||"",i=((g=document.getElementById("catalogTypeFilter"))==null?void 0:g.value)||"",s=((p=document.getElementById("catalogCategoryFilter"))==null?void 0:p.value)||"",n=((v=document.getElementById("catalogSort"))==null?void 0:v.value)||"latest",o=new URLSearchParams;a&&o.append("search",a),i&&o.append("comic_type",i),s&&o.append("category_id",s),o.append("sort",n);const l=await m(`/books?${o.toString()}`);if(l.ok&&l.data.success){if(c=l.data.data,t&&(t.textContent=`Menampilkan ${c.length} komik`),c.length===0){e.innerHTML=`
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <i class="fas fa-book-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"></i>
                    <h3>Komik tidak ditemukan</h3>
                    <p style="color: var(--text-secondary);">Coba ubah kata kunci pencarian atau ganti filter tipe komik.</p>
                </div>
            `;return}e.innerHTML=c.map($=>k($)).join(""),h()}}function k(e){const t=e.comic_type==="Manga"?"badge-manga":e.comic_type==="Manhwa"?"badge-manhwa":"badge-manhua",a=e.stock>0?`<span class="stock-badge in-stock"><i class="fas fa-check"></i> Stok: ${e.stock}</span>`:'<span class="stock-badge out-of-stock"><i class="fas fa-times"></i> Habis</span>';return`
        <div class="comic-card">
            <div class="comic-cover-wrapper">
                <img src="${y(e.cover_url||e.cover_image)}" alt="${e.title}" class="comic-cover" loading="lazy">
                <span class="comic-type-badge ${t}">${e.comic_type}</span>
                ${e.is_popular?'<span class="popular-ribbon"><i class="fas fa-fire"></i> Populer</span>':""}
            </div>
            <div class="comic-card-body">
                <div class="comic-genre">${e.genre||"Action, Fantasy"}</div>
                <h4 class="comic-title" title="${e.title}">${e.title}</h4>
                <div class="comic-meta">
                    <span><i class="fas fa-pen-nib"></i> ${e.author}</span>
                </div>
                <div class="comic-price-row">
                    <div class="comic-price">${f(e.price)}</div>
                    ${a}
                </div>
                <div class="comic-actions">
                    <button class="btn btn-secondary btn-sm btn-detail" data-id="${e.id}">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    <button class="btn btn-primary btn-sm btn-add-cart" data-id="${e.id}" ${e.stock<=0?"disabled":""}>
                        <i class="fas fa-cart-plus"></i> ${e.stock>0?"+ Keranjang":"Habis"}
                    </button>
                </div>
            </div>
        </div>
    `}function h(){document.querySelectorAll(".btn-detail").forEach(e=>{e.addEventListener("click",t=>{const a=Number(t.currentTarget.getAttribute("data-id"));C(a)})}),document.querySelectorAll(".btn-add-cart").forEach(e=>{e.addEventListener("click",t=>{const a=Number(t.currentTarget.getAttribute("data-id")),i=c.find(s=>s.id===a);i&&b(i,1)})})}function C(e){var s;const t=c.find(n=>n.id===e);if(!t)return;let a=document.getElementById("comicDetailModal");a||(a=document.createElement("div"),a.id="comicDetailModal",a.className="modal-overlay",document.body.appendChild(a));const i=y(t.cover_url||t.cover_image);a.innerHTML=`
        <div class="modal-content modal-detail-layout">
            <div class="modal-header">
                <h3>Detail Komik: ${t.title}</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('comicDetailModal').classList.remove('active')">&times;</button>
            </div>
            <div class="modal-body detail-grid">
                <div class="detail-cover-area">
                    <img src="${i}" alt="${t.title}" class="detail-cover-img">
                </div>
                <div class="detail-info-area">
                    <span class="badge badge-info" style="font-size: 0.85rem;">Tipe: ${t.comic_type}</span>
                    <h2 style="margin: 8px 0; font-size: 1.5rem; color: var(--text-primary);">${t.title}</h2>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary-color); margin-bottom: 15px;">
                        ${f(t.price)}
                    </div>

                    <div class="detail-specs">
                        <div><strong>Penulis/Komikus:</strong> ${t.author}</div>
                        <div><strong>Penerbit:</strong> ${t.publisher}</div>
                        <div><strong>Genre:</strong> ${t.genre}</div>
                        <div><strong>Tahun Rilis:</strong> ${t.year}</div>
                        <div><strong>Sisa Stok:</strong> <span class="badge ${t.stock>0?"badge-success":"badge-danger"}">${t.stock} unit</span></div>
                    </div>

                    <h4 style="margin: 15px 0 6px; font-size: 0.95rem;">Sinopsis / Deskripsi:</h4>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6;">
                        ${t.description||"Kisah petualangan komik seru dengan visual berkualitas tinggi."}
                    </p>

                    <div style="display: flex; gap: 12px; align-items: center; margin-top: 25px;">
                        <input type="number" id="detailQtyInput" min="1" max="${t.stock}" value="1" class="form-control" style="width: 80px;" ${t.stock<=0?"disabled":""}>
                        <button type="button" id="btnDetailAddToCart" class="btn btn-primary" style="flex: 1;" ${t.stock<=0?"disabled":""}>
                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,a.classList.add("active"),(s=document.getElementById("btnDetailAddToCart"))==null||s.addEventListener("click",()=>{var o;const n=Number((o=document.getElementById("detailQtyInput"))==null?void 0:o.value)||1;b(t,n)&&a.classList.remove("active")}),a.addEventListener("click",n=>{n.target===a&&a.classList.remove("active")})}function L(e,t){let a;return function(...i){clearTimeout(a),a=setTimeout(()=>e.apply(this,i),t)}}export{T as a,B as i};
