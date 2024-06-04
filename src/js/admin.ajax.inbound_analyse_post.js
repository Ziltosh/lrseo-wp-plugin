import {checkLrseoAdminPage} from "./admin.check_lrseo_page";
import {Store} from "./admin.cache";

jQuery(document).ready(function ($) {

    // On vérifie qu'on est sur la page adu plugin
    if (!checkLrseoAdminPage()) {
        return;
    }

    const mainBtn = $('#inbound_submit_posts_link')
    const progressBar = $('#inbound_progress_bar_posts_link');
    const progressBarText = $('#inbound_progress_bar_posts_link_text');

    mainBtn.on('click', function (e) {
        e.preventDefault();
        const checked = $('input[name="post[]"]:checked');
        const ids = [];
        checked.each(function () {
            ids.push($(this).data('id'));
        });

        if (ids.length === 0) {
            return;
        }

        const kw = $('#inbound_kw_post').val();

        if (!kw) {
            alert('Veuillez renseigner un mot-clé');
            return;
        }

        // On affiche la progress bar
        const bar = progressBar.find('.bar');
        progressBar.removeClass('lr-hidden');
        bar.attr('style', 'width: 0%;');
        progressBarText.text(`0/${ids.length}`);
        mainBtn.attr('disabled', true);
        mainBtn.text('Analyse des articles en cours...');

        const promises = [];
        const allResults = [];

        for (let i = 0; i < ids.length; i++) {
            const currentId = ids[i];
            const data = {
                action: 'lrseo_inbound_analyse_post',
                post_id_dst: ids[i],
                post_id_src: $('#inbound_src_post').val(),
                kw: kw,
                security: lrseo_inbound_analyse_post.nonce
            };

            if (Store.get(`lrseo_inbound_analyse_post_${currentId}`)) {
                allResults.push(Store.get(`lrseo_inbound_analyse_post_${currentId}`));
                progressBarText.text(`${allResults.length}/${ids.length}`);
                bar.attr('style', `width: ${Math.round((allResults.length / ids.length) * 100)}%;`);
                continue;
            }

            promises.push(new Promise(async (resolve) => {
                await new Promise(resolve => setTimeout(resolve, i * 100))
                $.post(lrseo_inbound_analyse_post.url, data, (response) => {
                    if (response.success) {
                        allResults.push(response.data);
                        // Store.store(response.data, `lrseo_inbound_analyse_post_${currentId}`, 60);
                        progressBarText.text(`${allResults.length}/${ids.length}`);
                        // Store.store(allResults, 'lrseo_inbound_select_post');
                        bar.attr('style', `width: ${Math.round((allResults.length / ids.length) * 100)}%;`);
                    }
                    resolve();
                })
            }))
        }

        Promise.all(promises).then(() => {
            progressBar.addClass('lr-hidden');
            displayResults(allResults)
        })

    })

    function displayResults(results) {
        console.log('displayResults', results)
        progressBar.addClass('lr-hidden');
        mainBtn.attr('disabled', false);
        mainBtn.text('Suggérer des liens');
        progressBarText.text('');

        const divResult = $("#inbound_posts_link_results");
        divResult.empty();

        if (results.length === 0) {
            divResult.append('<p>Aucun résultat</p>');
            return;
        }

        results.forEach((result, index) => {
            divResult.append(`
                <h3 class="title">${result.title_dst}</h3>
                <div class="lr-flex lr-flex-col lr-gap-2 lr-mb-2">
                    <div class="lr-flex lr-gap-2">
                        <div class="lr-self-center lr-w-1/6">Texte avant: </div>
                        <input class="lr-grow" type="text" id="inbound_text_before_${index}" value=""/>
                    </div>
                    <div class="lr-flex lr-gap-2">
                        <div class="lr-self-center lr-w-1/6">Phrase: </div>
                        <input class="lr-grow" type="text" id="inbound_text_sentence_${index}" value=""/>
                    </div>
                    <button type="button" id="inbound_edit_article_${index}" class="button button-secondary">Aller sur la modification de l'article</button>
                </div>
                <hr/>
            `)
            $('#inbound_text_before_' + index).val(result.before)
            $('#inbound_text_sentence_' + index).val(result.sentence)
            $('#inbound_edit_article_' + index).on('click', function () {
                window.open('/wp-admin/post.php?post=' + result.id_dst + '&action=edit', '_blank')
            })
        })

    }

})