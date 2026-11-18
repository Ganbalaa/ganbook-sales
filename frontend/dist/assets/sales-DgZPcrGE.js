import{r as T,d as _,a as p,c as L,f as l,e as E}from"./script-Cjw_6am7.js";let y=[],v=[],r=[];document.addEventListener("DOMContentLoaded",async()=>{var n,t,a,s,o,d,i,c,u,m,h,I;if(!T(["admin"]))return;const e=_();e&&(document.getElementById("adminNameDisplay").textContent=e.name),(n=document.getElementById("btnAdminLogout"))==null||n.addEventListener("click",async()=>{if(confirm("Keluar dari sesi Administrator?")){try{await p("/logout","POST")}catch{}L(),window.location.href="login.html"}}),P(),await g(),await S(),(t=document.getElementById("searchSalesInput"))==null||t.addEventListener("input",b),(a=document.getElementById("filterOrderStatus"))==null||a.addEventListener("change",b),(s=document.getElementById("filterPaymentMethod"))==null||s.addEventListener("change",b),(o=document.getElementById("btnRefreshSales"))==null||o.addEventListener("click",g),(d=document.getElementById("posComicSelect"))==null||d.addEventListener("change",A),(i=document.getElementById("btnPosAddToCart"))==null||i.addEventListener("click",H),(c=document.getElementById("posCashInput"))==null||c.addEventListener("input",k),(u=document.getElementById("posPaymentMethod"))==null||u.addEventListener("change",B=>{const $=B.target.value==="tunai";document.getElementById("boxCashCalc").style.display=$?"grid":"none"}),(m=document.getElementById("btnPosReset"))==null||m.addEventListener("click",x),(h=document.getElementById("btnPosCheckout"))==null||h.addEventListener("click",q),(I=document.getElementById("btnSaveStatusChange"))==null||I.addEventListener("click",N)});function P(){const e=document.getElementById("tabBtnHistory"),n=document.getElementById("tabBtnCreate"),t=document.getElementById("sectionSalesHistory"),a=document.getElementById("sectionCreateSale");e.addEventListener("click",()=>{e.className="btn btn-primary",n.className="btn btn-secondary",t.style.display="block",a.style.display="none"}),n.addEventListener("click",()=>{n.className="btn btn-primary",e.className="btn btn-secondary",a.style.display="block",t.style.display="none"})}async function g(){const e=await p("/sales");e.ok&&e.data.success&&(y=e.data.data,M(y),C(y))}function M(e){const n=e.length,t=e.reduce((s,o)=>s+Number(o.total_price),0),a=e.reduce((s,o)=>s+Number(o.total_items),0);document.getElementById("statTotalSalesCount").textContent=`${n} Pesanan`,document.getElementById("statTotalRevenue").textContent=l(t),document.getElementById("statTotalComicsSold").textContent=`${a} Volume`}function C(e){const n=document.getElementById("salesTableBody");if(n){if(e.length===0){n.innerHTML='<tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">Tidak ada data transaksi yang sesuai.</td></tr>';return}n.innerHTML=e.map(t=>{const a=t.order_status==="completed"?'<span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai</span>':t.order_status==="processing"?'<span class="badge badge-info"><i class="fas fa-shipping-fast"></i> Diproses</span>':t.order_status==="cancelled"?'<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Batal</span>':'<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>',s=t.payment_method==="qris"?'<span class="badge" style="background: #e0f2fe; color: #0369a1;"><i class="fas fa-qrcode"></i> QRIS</span>':t.payment_method==="cod"?'<span class="badge" style="background: #fef3c7; color: #92400e;"><i class="fas fa-truck"></i> COD</span>':'<span class="badge" style="background: #f1f5f9; color: #334155;"><i class="fas fa-money-bill-wave"></i> Tunai</span>';return`
                    <tr>
                        <td><strong>#${t.invoice_number}</strong></td>
                        <td>
                            <div><strong>${t.customer_name}</strong></div>
                            <small class="text-muted">${t.customer_phone||"-"}</small>
                        </td>
                        <td>${E(t.sale_date)}</td>
                        <td><span class="badge badge-info">${t.total_items} komik</span></td>
                        <td><strong>${l(t.total_price)}</strong></td>
                        <td>${s}</td>
                        <td>${a}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button class="btn btn-secondary btn-sm" onclick="window.viewReceipt(${t.id})" title="Lihat Faktur">
                                    <i class="fas fa-file-invoice"></i>
                                </button>
                                <button class="btn btn-primary btn-sm" onclick="window.openStatusModal(${t.id}, '${t.invoice_number}', '${t.order_status}', '${t.payment_status}')" title="Ubah Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `}).join("")}}function b(){var s,o,d;const e=((s=document.getElementById("searchSalesInput"))==null?void 0:s.value.toLowerCase().trim())||"",n=((o=document.getElementById("filterOrderStatus"))==null?void 0:o.value)||"",t=((d=document.getElementById("filterPaymentMethod"))==null?void 0:d.value)||"",a=y.filter(i=>{const c=!e||i.invoice_number.toLowerCase().includes(e)||i.customer_name.toLowerCase().includes(e),u=!n||i.order_status===n,m=!t||i.payment_method===t;return c&&u&&m});C(a)}window.openStatusModal=(e,n,t,a)=>{document.getElementById("editStatusSaleId").value=e,document.getElementById("editStatusInvoice").value="#"+n,document.getElementById("editOrderStatus").value=t||"processing",document.getElementById("editPaymentStatus").value=a||"unpaid",document.getElementById("updateStatusModal").classList.add("active")};async function N(){var s;const e=document.getElementById("editStatusSaleId").value,n=document.getElementById("editOrderStatus").value,t=document.getElementById("editPaymentStatus").value,a=await p(`/sales/${e}/status`,"PATCH",{order_status:n,payment_status:t});a.ok&&a.data.success?(alert("Status pesanan berhasil diperbarui!"),document.getElementById("updateStatusModal").classList.remove("active"),await g()):alert(((s=a.data)==null?void 0:s.message)||"Gagal memperbarui status pesanan.")}async function S(){const e=await p("/books");if(e.ok&&e.data.success){v=e.data.data;const n=document.getElementById("posComicSelect");if(!n)return;n.innerHTML='<option value="">-- Pilih Judul Komik --</option>'+v.map(t=>`
                        <option value="${t.id}" data-price="${t.price}" data-stock="${t.stock}">
                            [${t.comic_type}] ${t.title} (Sisa: ${t.stock}) - ${l(t.price)}
                        </option>
                    `).join("")}}function A(){const e=document.getElementById("posComicSelect"),n=document.getElementById("posComicInfo"),t=e.options[e.selectedIndex];if(!t||!t.value){n.textContent="Pilih komik pada daftar di atas.";return}const a=t.getAttribute("data-price"),s=t.getAttribute("data-stock");n.innerHTML=`Harga: <strong>${l(a)}</strong> | Sisa Stok Fisik: <strong>${s} volume</strong>`}function H(){const e=document.getElementById("posComicSelect"),n=Number(e.value),t=Number(document.getElementById("posQuantity").value)||1;if(!n){alert("Silakan pilih komik terlebih dahulu!");return}const a=v.find(i=>i.id===n);if(!a)return;const s=r.find(i=>i.id===a.id),d=(s?s.quantity:0)+t;if(d>a.stock){alert(`Stok komik tidak mencukupi! Sisa stok tersedia: ${a.stock}`);return}s?s.quantity=d:r.push({id:a.id,title:a.title,price:a.price,stock:a.stock,quantity:t}),e.value="",document.getElementById("posQuantity").value=1,document.getElementById("posComicInfo").textContent="Pilih komik pada daftar di atas.",f()}function f(){const e=document.getElementById("posCartTableBody"),n=document.getElementById("posTotalDisplay"),t=document.getElementById("posTotalHidden");if(!e)return;if(r.length===0){e.innerHTML='<tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 18px;">Belum ada komik yang dipilih.</td></tr>',n.textContent="Rp 0",t.value=0,k();return}let a=0;e.innerHTML=r.map((s,o)=>{const d=s.price*s.quantity;return a+=d,`
                    <tr>
                        <td><strong>${s.title}</strong></td>
                        <td>${l(s.price)}</td>
                        <td style="width: 80px;">
                            <input type="number" min="1" max="${s.stock}" value="${s.quantity}" class="form-control" style="padding: 2px 6px;" onchange="window.updateAdminItemQty(${o}, this.value)">
                        </td>
                        <td><strong>${l(d)}</strong></td>
                        <td style="width: 40px;">
                            <button class="btn btn-danger btn-sm" onclick="window.removeAdminItem(${o})"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                `}).join(""),n.textContent=l(a),t.value=a,k()}window.updateAdminItemQty=(e,n)=>{const t=parseInt(n,10);if(isNaN(t)||t<=0)r.splice(e,1);else{const a=r[e];a.quantity=t>a.stock?a.stock:t}f()};window.removeAdminItem=e=>{r.splice(e,1),f()};function k(){var a,s;const e=Number((a=document.getElementById("posTotalHidden"))==null?void 0:a.value)||0,n=Number((s=document.getElementById("posCashInput"))==null?void 0:s.value)||0,t=document.getElementById("posChangeDisplay");if(t)if(n>0){const o=n-e;o>=0?(t.textContent=l(o),t.style.color="var(--success-color)"):(t.textContent=`Kurang ${l(Math.abs(o))}`,t.style.color="var(--danger-color)")}else t.textContent="Rp 0",t.style.color="var(--text-primary)"}function x(){r=[],document.getElementById("posCustomerName").value="Pembeli Toko",document.getElementById("posCustomerPhone").value="081234567890",document.getElementById("posCustomerAddress").value="Transaksi Toko GanBook",document.getElementById("posCashInput").value="",f()}async function q(){var u;if(r.length===0){alert("Daftar komik transaksi masih kosong!");return}const e=Number(document.getElementById("posTotalHidden").value),n=document.getElementById("posPaymentMethod").value;let t=Number(document.getElementById("posCashInput").value);if(n==="tunai"){if(!t||t<e){alert("Nominal uang tunai tidak mencukupi total belanja!");return}}else t=e;const a=document.getElementById("posCustomerName").value.trim()||"Pembeli Toko",s=document.getElementById("posCustomerPhone").value.trim()||"081234567890",o=document.getElementById("posCustomerAddress").value.trim()||"Transaksi Toko GanBook",d={customer_name:a,customer_phone:s,customer_address:o,payment_method:n,notes:"Transaksi penjualan langsung via Sistem Admin GanBook Sales",items:r.map(m=>({book_id:m.id,quantity:m.quantity}))},i=document.getElementById("btnPosCheckout");i.disabled=!0,i.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan Transaksi...';const c=await p("/sales","POST",d);if(i.disabled=!1,i.innerHTML='<i class="fas fa-check-circle"></i> Simpan & Cetak Faktur',c.ok&&c.data.success){const m=c.data.data;w(m,t,t-e),x(),await S(),await g()}else alert(((u=c.data)==null?void 0:u.message)||"Gagal memproses transaksi penjualan.")}function w(e,n=null,t=null){const a=document.getElementById("receiptModal"),s=document.getElementById("receiptContent");if(!a||!s)return;const o=e.sale_details||[],d=n!==null?n:e.total_price,i=t!==null?t:0;s.innerHTML=`
                <div style="font-family: 'Courier New', Courier, monospace; background: #fff; padding: 14px; border: 1px dashed #cbd5e1; border-radius: 6px;">
                    <div style="text-align: center; border-bottom: 1px dashed #94a3b8; padding-bottom: 10px; margin-bottom: 10px;">
                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">GANBOOK SALES STORE</h3>
                        <p style="font-size: 0.8rem; color: #475569; margin: 2px 0;">Pusat Komik Fisik: Manga, Manhwa & Manhua</p>
                        <p style="font-size: 0.78rem; margin: 4px 0 0;">No. Faktur: <strong>#${e.invoice_number}</strong></p>
                        <p style="font-size: 0.78rem; margin: 0;">Waktu: ${E(e.sale_date)}</p>
                        <p style="font-size: 0.78rem; margin: 0;">Pelanggan: <strong>${e.customer_name}</strong></p>
                    </div>

                    <div style="border-bottom: 1px dashed #94a3b8; padding-bottom: 8px; margin-bottom: 8px; font-size: 0.82rem;">
                        ${o.map(c=>`
                            <div style="margin-bottom: 5px;">
                                <div><strong>${c.book?c.book.title:"Komik"}</strong></div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>${c.quantity} x ${l(c.unit_price)}</span>
                                    <span>${l(c.subtotal)}</span>
                                </div>
                            </div>
                        `).join("")}
                    </div>

                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 0.95rem; margin-bottom: 4px;">
                        <span>TOTAL TAGIHAN:</span>
                        <span>${l(e.total_price)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                        <span>Metode Bayar:</span>
                        <span style="text-transform: uppercase;"><strong>${e.payment_method}</strong></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                        <span>Status Pembayaran:</span>
                        <span><strong>${e.payment_status.toUpperCase()}</strong></span>
                    </div>
                    ${e.payment_method==="tunai"?`
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-top: 4px;">
                            <span>Uang Diterima:</span>
                            <span>${l(d)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: bold; color: var(--success-color);">
                            <span>Kembalian:</span>
                            <span>${l(i)}</span>
                        </div>
                    `:""}

                    <div style="text-align: center; margin-top: 14px; font-size: 0.75rem; color: #64748b;">
                        <p>Terima kasih atas pesanan Anda di GanBook Sales!</p>
                        <p>Dokumentasi resmi kompetensi Junior Web Developer BNSP.</p>
                    </div>
                </div>
            `,a.classList.add("active")}window.viewReceipt=async e=>{const n=await p(`/sales/${e}`);n.ok&&n.data.success&&w(n.data.data,n.data.data.total_price,0)};
