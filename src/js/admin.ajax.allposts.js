import {Store} from "./admin.cache";
import {checkLrseoAdminPage} from "./admin.check_lrseo_page";


jQuery(document).ready(function ($) {
    const searchInput = $('#lrseo_search_post')
    searchInput.val(Store.get('lrseo_search_post'));
    searchInput.on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $('#lrseo_allposts_tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    })
    searchInput.on('blur', function () {
        console.log($(this).val());
        Store.store($(this).val(), 'lrseo_search_post', 10);
    });

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
            const rows = posts.map(post =>
                `<tr data-title="${post.post_title}" data-id="${post.ID}" class="${parseInt(post_id, 10) === parseInt(post.ID, 10) && '!lr-bg-amber-100'}">
                    <td>${parseInt(post_id, 10) === parseInt(post.ID, 10) ? '' : `<a href="${window.location}&lrseo_inbound_post_select=${post.ID}">Choisir</a>`}</td>
                    <td class="title has-row-actions column-title column-primary lr-max-w-[800px] lr-overflow-y-scroll">
                        <strong>${post.post_title}</strong>
                    </td>
                    <td>${post.outbound_links?.length}</td>
                    <td>${post.inbound_links?.length}</td>
                    <td>${post.words}</td>
                </tr>`
            ).join('');

            $('#lrseo_allposts_tbody').html(rows);
            $('#lrseo_allposts_table').removeClass('lr-hidden')
            $('.lrseo_status').addClass('lr-hidden')

            const searchValue = Store.get('lrseo_search_post');
            $('#lrseo_allposts_tbody tr').filter(function () {
                if (!searchValue || searchValue === '') {
                    return true;
                }
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1)
            });
        }
    }
});
