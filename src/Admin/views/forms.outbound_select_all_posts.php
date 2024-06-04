<form class="lr-flex lr-flex-col lr-gap-2 lr-mb-2" action="#" class="form">
    <label>Choix de l'article de destination</label>
    <div id="lrseo-allposts" class="lr-flex lr-flex-col lr-gap-2">
        <div
            class="lr-inline-block lr-h-5 lr-w-5 lr-animate-spin lr-rounded-full lr-border-4 lr-border-solid lr-border-current lr-border-e-transparent lr-align-[-0.125em] lr-text-surface lr-motion-reduce:animate-[spin_1.5s_linear_infinite] lr-text-black"
            role="status">
        </div>
    </div>
    <?php wp_nonce_field('outbound_post_select','outbound_post_select_nonce'); ?>
    <label for="outbound_kw_post" class="lr-block lr-mt-2">Mot-clé désiré (lié à l'article choisi), ce sera l'ancre du lien crée: </label>
    <input type="text" name="outbound_kw_post" id="outbound_kw_post"/>
    <input name="action" value="outbound_post_select" type="hidden">
    <button id="outbound_select_post" type="button" class="button button-primary">Valider le choix de l'article</button>
</form>
