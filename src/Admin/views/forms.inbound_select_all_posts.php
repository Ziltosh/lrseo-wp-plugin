<form class="lr-flex lr-flex-col lr-gap-2 lr-mb-2" action="#" class="form">
    <label>Choix de l'article de destination</label>
    <div id="lrseo-allposts" class="lr-flex lr-flex-col lr-gap-2">
        <div
            class="lr-inline-block lr-h-5 lr-w-5 lr-animate-spin lr-rounded-full lr-border-4 lr-border-solid lr-border-current lr-border-e-transparent lr-align-[-0.125em] lr-text-surface lr-motion-reduce:animate-[spin_1.5s_linear_infinite] lr-text-black"
            role="status">
        </div>
    </div>
    <?php wp_nonce_field('inbound_post_select','inbound_post_select_nonce'); ?>
    <label for="inbound_kw_post" class="lr-block lr-mt-2">Mot-clé désiré (lié à l'article choisi), ce sera l'ancre du lien crée: </label>
    <input type="text" name="inbound_kw_post" id="inbound_kw_post"/>
    <input name="action" value="inbound_post_select" type="hidden">
    <button id="inbound_select_post" type="button" class="button button-primary">Voir les articles sources potentiels</button>
</form>
