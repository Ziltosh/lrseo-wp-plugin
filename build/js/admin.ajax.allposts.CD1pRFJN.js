import{S as t}from"./admin.cache.4-osdmxG.js";import{c as p}from"./admin.check_lrseo_page.Ci4yMWoH.js";jQuery(document).ready(function(s){const r={action:"lrseo_allposts",security:lrseo_allposts.nonce};if(!p())return;t.get("lrseo_allposts")===null?s.post(lrseo_allposts.url,r,e=>{e.success&&(t.store(e.data,"lrseo_allposts",10),o())}):o();function o(){let e=t.get("lrseo_allposts"),i=new URLSearchParams(window.location.search).get("lrseo_inbound_post_select");if(e){const c=`<select class="lr-grow !lr-max-w-full lr-w-full" name="post_id">${e.map(l=>{var n,a;return`<option value="${l.ID}" ${parseInt(i,10)===parseInt(l.ID,10)&&"selected"} data-title="${l.post_title.replaceAll('"',"")}">${l.post_title} (${((n=l.outbound_links)==null?void 0:n.length)||0} OUT / ${((a=l.inbound_links)==null?void 0:a.length)||0} IN)</option>`}).join("")}</select>`;s("#lrseo-allposts").html(c)}}});
