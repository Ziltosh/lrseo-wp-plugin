import {Store} from "./admin.cache";
import {checkLrseoAdminPage} from "./admin.check_lrseo_page";


jQuery(document).ready(function ($) {
    const data = {action: 'lrseo_allposts', security: lrseo_allposts.nonce};
    // On vérifie qu'on est sur la page adu plugin
    if (!checkLrseoAdminPage()) {
        return;
    }

    if (Store.get('lrseo_allposts') === null) {
        $.post(lrseo_allposts.url, data, (response) => {
            if (response.success) {
                Store.store(response.data, 'lrseo_allposts', 10);
                displaySelectPosts();
            }
        });
    } else {
        displaySelectPosts();
    }

    function displaySelectPosts() {
        let posts = Store.get('lrseo_allposts');
        let query = new URLSearchParams(window.location.search);
        let post_id = query.get('lrseo_inbound_post_select');
        if (posts) {
            const options = posts.map(post =>
                `<option value="${post.ID}" ${parseInt(post_id, 10) === parseInt(post.ID, 10) && 'selected'} data-title="${post.post_title.replaceAll('"', '')}">${post.post_title} (${post.outbound_links?.length || 0} OUT / ${post.inbound_links?.length || 0} IN)</option>`
            ).join('');

            const select = `<select class="lr-grow !lr-max-w-full lr-w-full" name="post_id">${options}</select>`;

            $('#lrseo-allposts').html(select);
        }
    }
});
