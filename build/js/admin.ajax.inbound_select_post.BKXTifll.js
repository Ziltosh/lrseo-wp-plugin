var y=(e,r,i)=>new Promise((l,o)=>{var a=n=>{try{t(i.next(n))}catch(d){o(d)}},s=n=>{try{t(i.throw(n))}catch(d){o(d)}},t=n=>n.done?l(n.value):Promise.resolve(n.value).then(a,s);t((i=i.apply(e,r)).next())});import{S as v}from"./admin.cache.4-osdmxG.js";import{c as x}from"./admin.check_lrseo_page.Ci4yMWoH.js";jQuery(document).ready(function(e){if(!x())return;const r=e("#inbound_select_post");r.on("click",function(l){l.preventDefault();const o=e('select[name="post_id"]').val(),a=e("#inbound_kw_post").val(),s=[];if(!a){alert("Veuillez renseigner un mot-clé");return}r.attr("disabled",!0),r.text("Analyse des articles en cours...");const t=v.get(`lrseo_inbound_select_post_${o}`);if(t){s.push(...t),i(s,o);return}let n=e('input[name="liste"]').val();n=atob(n),n=n.split(`
`).filter(c=>{const h=JSON.parse(c);return parseInt(h.id,10)!==parseInt(o,10)}).join(`
`);const d=new URL(window.location.href);d.searchParams.set("lrseo_inbound_post_select",o),window.history.replaceState({},"",d);const _=e("#inbound_progress_bar"),m=e("#inbound_progress_bar_text"),b=_.find(".bar");_.removeClass("lr-hidden"),b.attr("style","width: 0%;");const f=n.split(`
`),u=f.length,w=10,g=[];m.text(`0/${u}`);for(let c=0;c<u;c+=w){const h={action:"lrseo_inbound_select_post",kw:a,liste:f.slice(c,c+w).join(`
`),security:lrseo_inbound_select_post.nonce};g.push(new Promise(k=>y(this,null,function*(){yield new Promise(p=>setTimeout(p,c*100)),e.post(lrseo_inbound_select_post.url,h,p=>{p.success&&(s.push(...p.data),m.text(`${s.length}/${u}`),b.attr("style",`width: ${Math.round(s.length/u*100)}%;`)),k()})})))}Promise.all(g).then(()=>{_.addClass("lr-hidden"),v.store(s,`lrseo_inbound_select_post_${o}`,60),i(s,o)})});function i(l,o){l.sort((s,t)=>t.score-s.score),e("#inbound_table_results").removeClass("lr-hidden"),e("#inbound_results").removeClass("lr-hidden"),e("#inbound_src_post").val(o),r.attr("disabled",!1),r.text("Valider le choix de l'article");const a=e("#inbound_tbody_results");if(a.html(""),l){const s=l.map(t=>`<tr>
                    <th scope="row" class="check-column"><input type="checkbox" name="post[]" ${t.score>=85&&"checked"} data-id="${t.id}" data-title="${t.title}"/></th>
                    <td class="title has-row-actions column-title column-primary">
                        <strong>${t.titre||t.title||""}</strong>
                        <div class="row-actions">
                            <span class="edit"><a href="/wp-admin/post.php?post=${t.id}&action=edit">Modifier</a> | </span>
                            <span class="view"><a href="/?p=${t.id}" target="_blank">Voir</a></span>
                        </div>
                    </td>
                    <td>${t.score}</td>
                </tr>`).join("");a.html(s)}}});
