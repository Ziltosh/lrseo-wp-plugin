import{c as v,S as w}from"./admin.check_lrseo_page-Bs1S3pWX.js";jQuery(document).ready(function(e){if(!v())return;const n=e("#inbound_select_post");n.on("click",function(i){i.preventDefault();let s=new URLSearchParams(window.location.search).get("lrseo_inbound_post_select");const o=e("#inbound_kw_post").val(),t=e('tr[data-id="'+s+'"]').data("title"),r=[];if(!o){alert("Veuillez renseigner un mot-clé");return}n.attr("disabled",!0),n.text("Analyse des articles en cours...");const p=w.get(`lrseo_inbound_select_post_${s}_${o}`);if(p){r.push(...p),u(r,s);return}const d=e("#inbound_progress_bar"),m=e("#inbound_progress_bar_text"),b=d.find(".bar");d.removeClass("lr-hidden"),b.attr("style","width: 0%;");const l=e("#lrseo_allposts_tbody tr").length-1,h=30,f=[];m.text(`0/${l}`);for(let a=0;a<l;a+=h){const y={action:"lrseo_inbound_select_post",kw:o,title:t,post_id:s,current:a,step:h,security:lrseo_inbound_select_post.nonce};f.push(new Promise(async g=>{await new Promise(c=>setTimeout(c,a*50)),e.post(lrseo_inbound_select_post.url,y,c=>{c.success&&(r.push(...c.data),m.text(`${r.length}/${l}`),b.attr("style",`width: ${Math.round(r.length/l*100)}%;`)),g()})}))}Promise.all(f).then(()=>{d.addClass("lr-hidden"),w.store(r,`lrseo_inbound_select_post_${s}_${o}`,60),u(r,s)})});function u(i,_){i.sort((o,t)=>t.score-o.score),e("#inbound_table_results").removeClass("lr-hidden"),e("#inbound_results").removeClass("lr-hidden"),e("#inbound_src_post").val(_),n.attr("disabled",!1),n.text("Voir les articles sources potentiels");const s=e("#inbound_tbody_results");if(s.html(""),i){const o=i.map(t=>`<tr>
                    <th scope="row" class="check-column"><input type="checkbox" name="post[]"} data-id="${t.id}" data-title="${t.title}"/></th>
                    <td class="title has-row-actions column-title column-primary lr-max-w-[800px] lr-overflow-y-scroll">
                        <strong>${t.titre||t.title||""}</strong>
                        <div class="row-actions">
                            <span class="edit"><a href="/wp-admin/post.php?post=${t.id}&action=edit">Modifier</a> | </span>
                            <span class="view"><a href="/?p=${t.id}" target="_blank">Voir</a></span>
                        </div>
                    </td>
                    <td>${t.score}</td>
                    <td>${Intl.NumberFormat("fr-FR",{maximumFractionDigits:2}).format(t.pct_links)}</td>
                </tr>`).join("");s.html(o)}}});
//# sourceMappingURL=lrseo_inbound_select_post-BvPj_puf.js.map
