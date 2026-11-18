import{r as m,a as r,c as u,e as f}from"./script-Cjw_6am7.js";document.addEventListener("DOMContentLoaded",async()=>{var e,t;m(["admin"])&&((e=document.getElementById("btnAdminLogout"))==null||e.addEventListener("click",async()=>{if(confirm("Keluar dari sesi Administrator?")){try{await r("/logout","POST")}catch{}u(),window.location.href="login.html"}}),await o(),(t=document.getElementById("filterUserRole"))==null||t.addEventListener("change",o))});async function o(){var d;const e=document.getElementById("adminUsersTableBody");if(!e)return;const t=((d=document.getElementById("filterUserRole"))==null?void 0:d.value)||"",n=t?`/users?role=${t}`:"/users",s=await r(n);if(s.ok&&s.data.success){const i=s.data.data;e.innerHTML=i.map((a,c)=>{const l=a.role==="admin"?'<span class="badge" style="background: #fef08a; color: #854d0e;"><i class="fas fa-shield-alt"></i> ADMIN</span>':'<span class="badge badge-success"><i class="fas fa-user"></i> CUSTOMER</span>';return`
                        <tr>
                            <td>${c+1}</td>
                            <td><strong>${a.name}</strong></td>
                            <td>${a.email}</td>
                            <td>${a.phone||"-"}</td>
                            <td>${l}</td>
                            <td>${f(a.created_at)}</td>
                        </tr>
                    `}).join("")}}
