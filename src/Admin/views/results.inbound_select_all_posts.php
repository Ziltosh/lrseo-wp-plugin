<div id="inbound_results" class="lr-hidden">
    <h2 class="title">Résultats</h2>
    <p>Voici les articles triés par score de pertinence pour faire des liens entrants vers l'article.</p>
    <p>Sélectionnez-en un ou plusieurs et validez pour avoir des suggestions de texte et de placement de liens.</p>
    <div class="lr-max-h-[1500px] lr-overflow-y-scroll">
        <table id="inbound_table_results" class="wp-list-table striped widefat table-view-list lr-mt-2 lr-hidden">
            <thead>
            <tr class="iedit level-0 type-page hentry">
                <td id="cb" class="manage-column column-cb check-column"><input type="checkbox">
                    <label for="cb-select-all-1"><span class="screen-reader-text">Tout sélectionner</span></label></td>
                <th class="manage-column column-title">Titre</th>
                <th>Score</th>
                <th>‰ liens</th>
            </tr>
            </thead>
            <tbody id="inbound_tbody_results">
            </tbody>
        </table>
    </div>

    <?php include 'results.inbound_analyse_posts.php'; ?>
</div>