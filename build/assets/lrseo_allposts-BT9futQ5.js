import{S as o,c as u}from"./admin.check_lrseo_page-Bs1S3pWX.js";jQuery(document).ready(function(t){const l=t("#lrseo_search_post");l.val(o.get("lrseo_search_post")),l.on("keyup",function(){const e=t(this).val().toLowerCase();t("#lrseo_allposts_tbody tr").filter(function(){t(this).toggle(t(this).text().toLowerCase().indexOf(e)>-1)})}),l.on("blur",function(){console.log(t(this).val()),o.store(t(this).val(),"lrseo_search_post",10)});const d={action:"lrseo_allposts",security:lrseo_allposts.nonce};if(!u())return;o.get("lrseo_allposts")===null?t.post(lrseo_allposts.url,d,e=>{e.success&&(o.store(e.data,"lrseo_allposts",10),a())}):a();function a(){let e=o.get("lrseo_allposts"),n=new URLSearchParams(window.location.search).get("lrseo_inbound_post_select");if(e){const _=e.map(s=>{var i,c;return`<tr data-title="${s.post_title}" data-id="${s.ID}" class="${parseInt(n,10)===parseInt(s.ID,10)&&"!lr-bg-amber-100"}">
                    <td>${parseInt(n,10)===parseInt(s.ID,10)?"":`<a href="${window.location}&lrseo_inbound_post_select=${s.ID}">Choisir</a>`}</td>
                    <td class="title has-row-actions column-title column-primary lr-max-w-[800px] lr-overflow-y-scroll">
                        <strong>${s.post_title}</strong>
                    </td>
                    <td>${(i=s.outbound_links)==null?void 0:i.length}</td>
                    <td>${(c=s.inbound_links)==null?void 0:c.length}</td>
                    <td>${s.words}</td>
                </tr>`}).join("");t("#lrseo_allposts_tbody").html(_),t("#lrseo_allposts_table").removeClass("lr-hidden"),t(".lrseo_status").addClass("lr-hidden");const r=o.get("lrseo_search_post");t("#lrseo_allposts_tbody tr").filter(function(){if(!r||r==="")return!0;t(this).toggle(t(this).text().toLowerCase().indexOf(r)>-1)})}}});
//# sourceMappingURL=lrseo_allposts-BT9futQ5.js.map
