import{d as y,b as m,f as i,a as h,s as v,i as b}from"./script-Cjw_6am7.js";function k(){if(!y()){alert("Silakan login terlebih dahulu untuk melakukan checkout pesanan."),window.location.href="login.html";return}if(m().length===0){alert("Keranjang belanja Anda kosong. Silakan pilih komik terlebih dahulu."),window.location.href="catalog.html";return}B(),x(),document.querySelectorAll('input[name="payment_method"]').forEach(o=>{o.addEventListener("change",s=>{const l=s.target.value;I(l)})});const n=document.getElementById("checkoutForm");n&&n.addEventListener("submit",E)}function x(){const t=y();if(!t)return;const e=document.getElementById("checkoutName"),a=document.getElementById("checkoutPhone"),n=document.getElementById("checkoutAddress");e&&!e.value&&(e.value=t.name||""),a&&!a.value&&(a.value=t.phone||""),n&&!n.value&&(n.value=t.address||"")}function B(){const t=document.getElementById("checkoutItemsList"),e=document.getElementById("checkoutTotalAmount");if(!t)return;const a=m();let n=0;t.innerHTML=a.map(o=>{const s=o.price*o.quantity;return n+=s,`
            <div class="checkout-item-row">
                <div>
                    <strong>${o.title}</strong>
                    <div class="text-muted" style="font-size: 0.8rem;">${o.quantity} x ${i(o.price)}</div>
                </div>
                <div><strong>${i(s)}</strong></div>
            </div>
        `}).join(""),e&&(e.textContent=i(n))}function I(t){const e=document.getElementById("qrisPaymentBox"),a=document.getElementById("codPaymentBox");t==="qris"?(e&&(e.style.display="block"),a&&(a.style.display="none")):(e&&(e.style.display="none"),a&&(a.style.display="block"))}async function E(t){var u,f,p;t.preventDefault();const e=m();if(e.length===0){alert("Keranjang belanja kosong!");return}const a=document.getElementById("checkoutName").value.trim(),n=document.getElementById("checkoutPhone").value.trim(),o=document.getElementById("checkoutAddress").value.trim(),s=((u=document.getElementById("checkoutNotes"))==null?void 0:u.value.trim())||"",l=((f=document.querySelector('input[name="payment_method"]:checked'))==null?void 0:f.value)||"cod",r=document.getElementById("btnSubmitOrder");r.disabled=!0,r.innerHTML='<i class="fas fa-spinner fa-spin"></i> Memproses Pesanan...';const g={customer_name:a,customer_phone:n,customer_address:o,payment_method:l,notes:s,items:e.map(c=>({book_id:c.id,quantity:c.quantity}))},d=await h("/sales","POST",g);if(r.disabled=!1,r.innerHTML='<i class="fas fa-lock"></i> Konfirmasi & Buat Pesanan',d.ok&&d.data.success){const c=d.data.data;v([]),S(c)}else alert(((p=d.data)==null?void 0:p.message)||"Gagal memproses pesanan komik.")}function S(t){let e=document.getElementById("orderSuccessModal");e||(e=document.createElement("div"),e.id="orderSuccessModal",e.className="modal-overlay",document.body.appendChild(e));const a=t.payment_method==="qris";e.innerHTML=`
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
                    <div style="font-size: 1.2rem; font-weight: 800; color: var(--primary-color);">#${t.invoice_number}</div>
                    <div style="font-size: 1rem; font-weight: 700; margin-top: 4px;">Total: ${i(t.total_price)}</div>
                </div>

                ${a?`
                    <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px; font-size: 0.82rem; color: #92400e; margin-bottom: 15px;">
                        <i class="fas fa-qrcode"></i> <strong>Pembayaran QRIS:</strong> Pembayaran Anda telah dikonfirmasi lunas oleh sistem.
                    </div>
                `:`
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px; font-size: 0.82rem; color: #166534; margin-bottom: 15px;">
                        <i class="fas fa-truck"></i> <strong>Metode COD:</strong> Siapkan uang tunai sebesar <strong>${i(t.total_price)}</strong> saat kurir mengantar pesanan.
                    </div>
                `}

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="orders.html" class="btn btn-primary" style="flex: 1;"><i class="fas fa-receipt"></i> Lihat Riwayat Pesanan</a>
                    <a href="catalog.html" class="btn btn-secondary" style="flex: 1;"><i class="fas fa-book"></i> Belanja Lagi</a>
                </div>
            </div>
        </div>
    `,e.classList.add("active")}document.addEventListener("DOMContentLoaded",()=>{b(),k()});
