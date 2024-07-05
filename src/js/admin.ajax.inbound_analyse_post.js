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

            promises.push(new Promise((resolve) => {
                setTimeout(() => {
                    $.post(lrseo_inbound_analyse_post.url, data, (response) => {
                        if (response.success) {
                            allResults.push(response.data);
                            progressBarText.text(`${allResults.length}/${ids.length}`);
                            bar.attr('style', `width: ${Math.round((allResults.length / ids.length) * 100)}%;`);
                        } else {
                            console.error(response.data);
                            // Tentative de nouvelle requête en cas d'échec
                            $.post(lrseo_inbound_analyse_post.url, data, (retryResponse) => {
                                if (retryResponse.success) {
                                    allResults.push(retryResponse.data);
                                    progressBarText.text(`${allResults.length}/${ids.length}`);
                                    bar.attr('style', `width: ${Math.round((allResults.length / ids.length) * 100)}%;`);
                                } else {
                                    console.error(retryResponse.data);
                                }
                            });
                        }
                        resolve();
                    });
                }, i * 10000); // Délai de 10 secondes multiplié par l'index
            }));
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

        let allHtml = '';
        results.forEach((resultsArticle, index) => {

            allHtml += `<div class="lr-p-6 lr-border-1 lr-mb-2 lr-border-dashed lr-border-gray-400 lr-bg-white">
                <h3 class="title">Article source: ${resultsArticle[0].title_dst}</h3>`
            resultsArticle.forEach((result, index2) => {
                const before = new TextEncoder().encode(result.before)
                const binString = Array.from(before, (byte) =>
                    String.fromCodePoint(byte),
                ).join("");
                const b64Before = btoa(binString)

                const sentence = new TextEncoder().encode(result.sentence)
                const binString2 = Array.from(sentence, (byte) =>
                    String.fromCodePoint(byte),
                ).join("");
                const b64Sentence = btoa(binString2)

                allHtml += `<div class="lr-flex lr-flex-col lr-gap-2 lr-mb-2">
                    <div class="lr-flex lr-gap-2">
                        <div class="lr-self-center lr-w-1/6">Texte avant: </div>
                        <div class="lrse_copy_clipboard lr-cursor-pointer lr-underline" data-html="${b64Before}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M9 8v3a1 1 0 0 1-1 1H5m11 4h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-7a1 1 0 0 0-1 1v1m4 3v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-7.13a1 1 0 0 1 .24-.65L7.7 8.35A1 1 0 0 1 8.46 8H13a1 1 0 0 1 1 1Z"/>
</svg>
</div>
                        <div id="inbound_html_before_${index}_${index2}">${result.before}</div>
                    </div>
                    <div class="lr-flex lr-gap-2">
                        <div class="lr-self-center lr-w-1/6">Phrase du lien: </div>
                        <div class="lrse_copy_clipboard lr-cursor-pointer lr-underline" data-html="${b64Sentence}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M9 8v3a1 1 0 0 1-1 1H5m11 4h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-7a1 1 0 0 0-1 1v1m4 3v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-7.13a1 1 0 0 1 .24-.65L7.7 8.35A1 1 0 0 1 8.46 8H13a1 1 0 0 1 1 1Z"/>
</svg>
</div>
                        <div id="inbound_html_sentence_${index}_${index2}">${result.sentence}</div>
                    </div>
                    <a type="button" href="/wp-admin/post.php?post=${result.id_dst}&action=edit" target="_blank" id="inbound_edit_article_${index}_${index2}" class="lr-max-w-[300px] button button-secondary">Aller sur la modification de l'article</a>
                </div>
                <hr />`;
            })

            allHtml += '</div>'
        })


        divResult.append(allHtml)

        $('.lrse_copy_clipboard').on('click', function () {
            const b64 = atob($(this).data('html'))
            const arrayStr = Uint8Array.from(b64, (m) => m.codePointAt(0));
            const html = new TextDecoder().decode(arrayStr)

            const type = "text/html";
            const blob = new Blob([html], { type });
            const data = [new ClipboardItem({ [type]: blob })];
            navigator.clipboard.write(data).then(function () {
                console.log('Copied to clipboard')
            }).catch(function (error) {
                console.error('Copy failed', error);
            });

        })
    }

})