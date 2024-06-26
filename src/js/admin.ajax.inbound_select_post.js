import "./admin.cache"
import {checkLrseoAdminPage} from "./admin.check_lrseo_page";
import {Store} from "./admin.cache";

jQuery(document).ready(function ($) {

    // On vérifie qu'on est sur la page adu plugin
    if (!checkLrseoAdminPage()) {
        return;
    }


    const mainBtn = $('#inbound_select_post')

    mainBtn.on('click', function (e) {
        e.preventDefault();
        let query = new URLSearchParams(window.location.search);
        let post_id = query.get('lrseo_inbound_post_select');
        const kw = $('#inbound_kw_post').val();
        const title = $('tr[data-id="'+post_id+'"]').data('title');
        const allResults = [];

        if (!kw) {
            alert('Veuillez renseigner un mot-clé');
            return;
        }

        mainBtn.attr('disabled', true);
        mainBtn.text('Analyse des articles en cours...');

        const storeResult = Store.get(`lrseo_inbound_select_post_${post_id}_${kw}`);
        if (storeResult) {
            allResults.push(...storeResult);
            displayResults(allResults, post_id)
            return;
        }

        // let liste = $('input[name="liste"]').val();
        // liste = atob(decodeURIComponent(liste));
        // // On enlève de la liste le post sélectionné
        // liste = liste.split('\n').filter(post => {
        //     const postObj = JSON.parse(post);
        //     return parseInt(postObj.id, 10) !== parseInt(postId, 10);
        // }).join('\n');

        // Change the url without navigate
        // const url = new URL(window.location.href);
        // url.searchParams.set('lrseo_inbound_post_select', postId);
        // window.history.replaceState({}, '', url);

        // On affiche la progress bar
        const progressBar = $('#inbound_progress_bar');
        const progressBarText = $('#inbound_progress_bar_text');
        const bar = progressBar.find('.bar');
        progressBar.removeClass('lr-hidden');
        bar.attr('style', 'width: 0%;');

        // On parcourt la liste et on envoie une requête avec a chaque fois 25 éléments
        // On attend que chaque requête soit terminée pour passer a la suivante
        // On met a jour la progress bar
        // const listeToArr = liste.split('\n');

        // Nombre d'options du select
        const total = $('#lrseo_allposts_tbody tr').length - 1;
        const step = 30;
        const promises = [];

        progressBarText.text(`0/${total}`);

        for (let i = 0; i < total; i += step) {
            const data = {
                action: 'lrseo_inbound_select_post',
                kw: kw,
                title: title,
                post_id: post_id,
                current: i,
                step: step,
                security: lrseo_inbound_select_post.nonce
            };

            promises.push(new Promise(async (resolve) => {
                await new Promise(resolve => setTimeout(resolve, i * 50))
                $.post(lrseo_inbound_select_post.url, data, (response) => {
                    if (response.success) {
                        allResults.push(...response.data);
                        progressBarText.text(`${allResults.length}/${total}`);
                        // Store.store(allResults, 'lrseo_inbound_select_post');
                        bar.attr('style', `width: ${Math.round((allResults.length / total) * 100)}%;`);
                    }
                    resolve();
                })
            }))
        }

        Promise.all(promises).then(() => {
            progressBar.addClass('lr-hidden');
            Store.store(allResults, `lrseo_inbound_select_post_${post_id}_${kw}`, 60);
            displayResults(allResults, post_id)
        })
    })

    function displayResults(posts, postId) {
        posts.sort((a, b) => b.score - a.score)
        $('#inbound_table_results').removeClass('lr-hidden')
        $('#inbound_results').removeClass('lr-hidden')
        $('#inbound_src_post').val(postId);
        mainBtn.attr('disabled', false);
        mainBtn.text('Voir les articles sources potentiels');
        const tbody = $('#inbound_tbody_results')
        tbody.html('');
        if (posts) {
            const rows = posts.map(post =>
                `<tr>
                    <th scope="row" class="check-column"><input type="checkbox" name="post[]"} data-id="${post.id}" data-title="${post.title}"/></th>
                    <td class="title has-row-actions column-title column-primary lr-max-w-[800px] lr-overflow-y-scroll">
                        <strong>${post.titre || post.title || ''}</strong>
                        <div class="row-actions">
                            <span class="edit"><a href="/wp-admin/post.php?post=${post.id}&action=edit">Modifier</a> | </span>
                            <span class="view"><a href="/?p=${post.id}" target="_blank">Voir</a></span>
                        </div>
                    </td>
                    <td>${post.score}</td>
                    <td>${Intl.NumberFormat('fr-FR', {maximumFractionDigits: 2}).format(post.pct_links)}</td>
                </tr>`
            ).join('');

            tbody.html(rows);
        }
    }
});