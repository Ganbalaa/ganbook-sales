import{i as m,r as f,a as p,e as c,f as o}from"./script-Cjw_6am7.js";document.addEventListener("DOMContentLoaded",async()=>{m(),f(["customer","pelanggan","admin"])&&b()});async function b(){const n=document.getElementById("ordersTableBody");if(!n)return;const e=await p("/sales");if(e.ok&&e.data.success){const t=e.data.data;if(t.length===0){n.innerHTML=`
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <i class="fas fa-box-open" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 12px;"></i>
                                <h3>Belum Ada Pesanan</h3>
                                <p style="color: var(--text-secondary); margin-bottom: 15px;">Anda belum melakukan pemesanan komik apa pun.</p>
                                <a href="catalog.html" class="btn btn-primary"><i class="fas fa-book"></i> Beli Komik Sekarang</a>
                            </td>
                        </tr>
                    `;return}n.innerHTML=t.map((a,i)=>{const r=a.order_status==="completed"?'<span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai</span>':a.order_status==="processing"?'<span class="badge badge-info"><i class="fas fa-shipping-fast"></i> Diproses</span>':'<span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu</span>',s=a.payment_method==="qris"?'<span class="badge" style="background: #e0f2fe; color: #0369a1;"><i class="fas fa-qrcode"></i> QRIS</span>':a.payment_method==="cod"?'<span class="badge" style="background: #fef3c7; color: #92400e;"><i class="fas fa-truck"></i> COD</span>':'<span class="badge" style="background: #f1f5f9; color: #334155;"><i class="fas fa-money-bill-wave"></i> Tunai</span>',l=a.sale_details&&a.sale_details.length>0?a.sale_details.map(d=>`${d.book?d.book.title:"Komik"} (${d.quantity}x)`).join(", "):`${a.total_items} komik`;return`
                        <tr>
                            <td>${i+1}</td>
                            <td><strong>#${a.invoice_number}</strong></td>
                            <td>${c(a.sale_date)}</td>
                            <td style="max-width: 250px; font-size: 0.85rem;" title="${l}">${l}</td>
                            <td><strong>${o(a.total_price)}</strong></td>
                            <td>${s}</td>
                            <td>${r}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="window.viewOrderDetail(${a.id})">
                                    <i class="fas fa-receipt"></i> Struk
                                </button>
                            </td>
                        </tr>
                    `}).join("")}}window.viewOrderDetail=async n=>{const e=await p(`/sales/${n}`);if(e.ok&&e.data.success){const t=e.data.data,a=document.getElementById("orderDetailModal"),i=document.getElementById("orderModalReceiptContent");if(!a||!i)return;const r=t.sale_details||[];i.innerHTML=`
                    <div style="font-family: 'Courier New', Courier, monospace; background: #fff; padding: 14px; border: 1px dashed #cbd5e1; border-radius: 6px;">
                        <div style="text-align: center; border-bottom: 1px dashed #94a3b8; padding-bottom: 8px; margin-bottom: 10px;">
                            <h3 style="margin: 0; font-size: 1.15rem;">GANBOOK SALES STORE</h3>
                            <p style="font-size: 0.8rem; color: #475569; margin: 2px 0;">Pusat Komik Manga, Manhwa, & Manhua</p>
                            <p style="font-size: 0.75rem; margin: 4px 0 0;">No: <strong>${t.invoice_number}</strong></p>
                            <p style="font-size: 0.75rem; margin: 0;">Waktu: ${c(t.sale_date)}</p>
                            <p style="font-size: 0.75rem; margin: 0;">Penerima: ${t.customer_name} (${t.customer_phone||"-"})</p>
                        </div>

                        <div style="border-bottom: 1px dashed #94a3b8; padding-bottom: 8px; margin-bottom: 8px; font-size: 0.85rem;">
                            ${r.map(s=>`
                                <div style="margin-bottom: 5px;">
                                    <div><strong>${s.book?s.book.title:"Komik"}</strong></div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>${s.quantity} x ${o(s.unit_price)}</span>
                                        <span>${o(s.subtotal)}</span>
                                    </div>
                                </div>
                            `).join("")}
                        </div>

                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 0.95rem; margin-bottom: 6px;">
                            <span>TOTAL:</span>
                            <span>${o(t.total_price)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #475569;">
                            <span>Metode Pembayaran:</span>
                            <span>${t.payment_method.toUpperCase()} (${t.payment_status.toUpperCase()})</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #475569;">
                            <span>Status Pesanan:</span>
                            <span>${t.order_status.toUpperCase()}</span>
                        </div>

                        <div style="text-align: center; margin-top: 14px; font-size: 0.75rem; color: #64748b;">
                            <p>Terima kasih telah berbelanja di GanBook Sales!</p>
                            <p>Simpan nota ini sebagai bukti transaksi resmi.</p>
                        </div>
                    </div>
                `,a.classList.add("active")}};
