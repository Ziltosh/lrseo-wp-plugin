import{S as s}from"./admin.cache.4-osdmxG.js";import{c as m}from"./admin.check_lrseo_page.Ci4yMWoH.js";jQuery(document).ready(function(l){const a={action:"lrseo_allposts",security:lrseo_allposts.nonce};if(!m())return;s.get("lrseo_allposts")===null?l.post(lrseo_allposts.url,a,e=>{e.success&&(s.store(e.data,"lrseo_allposts"),o())}):o();function o(){let e=s.get("lrseo_allposts"),r=new URLSearchParams(window.location.search).get("lrseo_inbound_post_select");if(e){const c=e.map(t=>{var n,i;return`<option value="${t.ID}" ${parseInt(r,10)===parseInt(t.ID,10)&&"selected"}>${t.post_title} (${((n=t.outbound_links)==null?void 0:n.length)||0} OUT / ${((i=t.inbound_links)==null?void 0:i.length)||0} IN)</option>`}).join(""),p=e.map(t=>JSON.stringify({id:t.ID,title:t.post_title})).join(`
`),u=btoa(p),d=`<select class="lr-grow !lr-max-w-full lr-w-full" name="post_id">${c}</select>`,_=`<input type="hidden" name="liste" value="${u}">`;l("#lrseo-allposts").html(d+_)}}});
