import{S as e}from"./admin.cache.4-osdmxG.js";import{c as p}from"./admin.check_lrseo_page.Ci4yMWoH.js";jQuery(document).ready(function(t){const a={action:"lrseo_allposts",security:lrseo_allposts.nonce};if(!p())return;e.get("lrseo_allposts")===null?t.post(lrseo_allposts.url,a,s=>{s.success&&(e.store(s.data,"lrseo_allposts"),o())}):o();function o(){let s=e.get("lrseo_allposts"),i=new URLSearchParams(window.location.search).get("lrseo_inbound_post_select");if(s){const c=`<select class="lr-grow !lr-max-w-full lr-w-full" name="post_id">${s.map(l=>{var n,r;return`<option value="${l.ID}" ${parseInt(i,10)===parseInt(l.ID,10)&&"selected"}>${l.post_title} (${((n=l.outbound_links)==null?void 0:n.length)||0} OUT / ${((r=l.inbound_links)==null?void 0:r.length)||0} IN)</option>`}).join("")}</select>`;t("#lrseo-allposts").html(c)}}});
