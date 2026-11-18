import{b as s,s as d,g as b,f as c,i as f}from"./script-Cjw_6am7.js";function k(){var o,t;l(),(o=document.getElementById("btnClearCart"))==null||o.addEventListener("click",()=>{confirm("Kosongkan semua komik di keranjang belanja?")&&(d([]),l())}),(t=document.getElementById("btnProceedCheckout"))==null||t.addEventListener("click",()=>{if(s().length===0){alert("Keranjang belanja Anda masih kosong!");return}if(!localStorage.getItem("ganbook_token")){alert("Silakan masuk (login) ke akun Anda terlebih dahulu untuk melakukan checkout pesanan."),window.location.href="login.html";return}window.location.href="checkout.html"})}function l(){const o=document.getElementById("cartItemsTableBody"),t=document.getElementById("cartSummaryTotalItems"),e=document.getElementById("cartSummarySubtotal"),r=document.getElementById("cartSummaryGrandTotal"),n=document.getElementById("btnProceedCheckout");if(!o)return;const i=s();if(i.length===0){o.innerHTML=`
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"></i>
                    <h3>Keranjang Belanja Kosong</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 20px;">Anda belum menambahkan komik apa pun ke keranjang.</p>
                    <a href="catalog.html" class="btn btn-primary"><i class="fas fa-book"></i> Jelajahi Katalog Komik</a>
                </td>
            </tr>
        `,t&&(t.textContent="0 Buku"),e&&(e.textContent="Rp 0"),r&&(r.textContent="Rp 0"),n&&(n.disabled=!0);return}n&&(n.disabled=!1);let m=0,u=0;o.innerHTML=i.map((a,y)=>{const g=a.price*a.quantity;return m+=a.quantity,u+=g,`
            <tr>
                <td style="width: 80px;">
                    <img src="${b(a.cover_image)}" alt="${a.title}" style="width: 60px; height: 85px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </td>
                <td>
                    <span class="badge badge-info" style="font-size: 0.72rem;">${a.comic_type}</span>
                    <h4 style="margin: 4px 0 2px; font-size: 0.95rem;">${a.title}</h4>
                    <span class="text-muted" style="font-size: 0.8rem;">Stok tersedia: ${a.stock}</span>
                </td>
                <td><strong>${c(a.price)}</strong></td>
                <td style="width: 140px;">
                    <div class="cart-qty-control">
                        <button type="button" class="btn-qty-minus" onclick="window.changeItemQty(${a.id}, -1)">-</button>
                        <input type="number" min="1" max="${a.stock}" value="${a.quantity}" class="form-control" style="text-align: center; width: 55px; padding: 4px;" onchange="window.setItemQty(${a.id}, this.value)">
                        <button type="button" class="btn-qty-plus" onclick="window.changeItemQty(${a.id}, 1)">+</button>
                    </div>
                </td>
                <td><strong>${c(g)}</strong></td>
                <td style="width: 50px;">
                    <button class="btn btn-danger btn-sm" onclick="window.removeCartItem(${a.id})" title="Hapus komik ini">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `}).join(""),t&&(t.textContent=`${m} Buku`),e&&(e.textContent=c(u)),r&&(r.textContent=c(u))}typeof window<"u"&&(window.changeItemQty=(o,t)=>{const e=s(),r=e.find(i=>i.id===o);if(!r)return;const n=r.quantity+t;if(n<=0){window.removeCartItem(o);return}if(n>r.stock){alert(`Jumlah melebihi batas stok tersedia (${r.stock})!`);return}r.quantity=n,d(e),l()},window.setItemQty=(o,t)=>{const e=parseInt(t,10),r=s(),n=r.find(i=>i.id===o);if(n){if(isNaN(e)||e<=0){window.removeCartItem(o);return}e>n.stock?(alert(`Jumlah melebihi batas stok (${n.stock})!`),n.quantity=n.stock):n.quantity=e,d(r),l()}},window.removeCartItem=o=>{let t=s();t=t.filter(e=>e.id!==o),d(t),l()});document.addEventListener("DOMContentLoaded",()=>{f(),k()});
