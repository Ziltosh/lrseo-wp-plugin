var b=(n,l,s)=>new Promise((d,u)=>{var o=e=>{try{t(s.next(e))}catch(r){u(r)}},i=e=>{try{t(s.throw(e))}catch(r){u(r)}},t=e=>e.done?d(e.value):Promise.resolve(e.value).then(o,i);t((s=s.apply(n,l)).next())});import{c as v}from"./admin.check_lrseo_page.Ci4yMWoH.js";import{S as f}from"./admin.cache.4-osdmxG.js";jQuery(document).ready(function(n){if(!v())return;const l=n("#inbound_submit_posts_link"),s=n("#inbound_progress_bar_posts_link"),d=n("#inbound_progress_bar_posts_link_text");l.on("click",function(o){o.preventDefault();const i=n('input[name="post[]"]:checked'),t=[];if(i.each(function(){t.push(n(this).data("id"))}),t.length===0)return;const e=n("#inbound_kw_post").val();if(!e){alert("Veuillez renseigner un mot-clé");return}const r=s.find(".bar");s.removeClass("lr-hidden"),r.attr("style","width: 0%;"),d.text(`0/${t.length}`),l.attr("disabled",!0),l.text("Analyse des articles en cours...");const p=[],a=[];for(let c=0;c<t.length;c++){const h=t[c],g={action:"lrseo_inbound_analyse_post",post_id_dst:t[c],post_id_src:n("#inbound_src_post").val(),kw:e,security:lrseo_inbound_analyse_post.nonce};if(f.get(`lrseo_inbound_analyse_post_${h}`)){a.push(f.get(`lrseo_inbound_analyse_post_${h}`)),d.text(`${a.length}/${t.length}`),r.attr("style",`width: ${Math.round(a.length/t.length*100)}%;`);continue}p.push(new Promise(m=>b(this,null,function*(){yield new Promise(_=>setTimeout(_,c*100)),n.post(lrseo_inbound_analyse_post.url,g,_=>{_.success&&(a.push(_.data),d.text(`${a.length}/${t.length}`),r.attr("style",`width: ${Math.round(a.length/t.length*100)}%;`)),m()})})))}Promise.all(p).then(()=>{s.addClass("lr-hidden"),u(a)})});function u(o){console.log("displayResults",o),s.addClass("lr-hidden"),l.attr("disabled",!1),l.text("Suggérer des liens"),d.text("");const i=n("#inbound_posts_link_results");if(i.empty(),o.length===0){i.append("<p>Aucun résultat</p>");return}o.forEach((t,e)=>{i.append(`
                <h3 class="title">${t.title_dst}</h3>
                <div class="lr-flex lr-flex-col lr-gap-2 lr-mb-2">
                    <div class="lr-flex lr-gap-2">
                        <div class="lr-self-center lr-w-1/6">Texte avant: </div>
                        <input class="lr-grow" type="text" id="inbound_text_before_${e}" value=""/>
                    </div>
                    <div class="lr-flex lr-gap-2">
                        <div class="lr-self-center lr-w-1/6">Phrase: </div>
                        <input class="lr-grow" type="text" id="inbound_text_sentence_${e}" value=""/>
                    </div>
                    <button type="button" id="inbound_edit_article_${e}" class="button button-secondary">Aller sur la modification de l'article</button>
                </div>
                <hr/>
            `),n("#inbound_text_before_"+e).val(t.before),n("#inbound_text_sentence_"+e).val(t.sentence),n("#inbound_edit_article_"+e).on("click",function(){window.open("/wp-admin/post.php?post="+t.id_dst+"&action=edit","_blank")})})}});
