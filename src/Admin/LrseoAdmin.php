<?php

namespace Admin;

class LrseoAdmin
{
    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'add_admin_menus']);
        Ajax::init();
        Forms::init();
    }

    public static function add_admin_menus()
    {
        add_menu_page('LRSEO', 'LRSEO', 'manage_options', 'lrseo', [__CLASS__, 'main_admin_page']);
    }

    public static function main_admin_page()
    {
        ?>

        <div class="wrap">
            <h1>LRSEO</h1>

            <?php if (!defined('OPENAI_KEY')): ?>
                <div class="notice notice-error">
                    <p>Vous devez définir la constante OPENAI_KEY dans votre fichier wp-config.php pour utiliser cette
                        page.</p>
                </div>
                <?php return ?>
            <?php endif; ?>


            <div class="lr-flex lr-flex-grow lr-gap-4">
                <div class="lr-gap-2 lr-w-full">
                    <h2 class="title">Faire un lien entrant</h2>
                    <?php include 'views/forms.inbound_select_all_posts.php'; ?>
                    <div id="inbound_progress_bar" class="lr-w-full lr-bg-gray-200 lr-rounded-full lr-h-2.5 lr-hidden lr-mb-2">
                        <div class="bar lr-bg-blue-600 lr-h-2.5 lr-rounded-full lr-transition" style="width: 45%"></div>
                    </div>
                    <div id="inbound_progress_bar_text" class="lr-w-full lr-text-center"></div>

                    <?php include 'views/results.inbound_select_all_posts.php'; ?>
                </div>
<!--                <div class="lr-w-1/2 lr-gap-2">-->
<!--                    <h2 class="title">Faire un lien sortant</h2>-->
<!--                    --><?php //include 'views/forms.outbound_select_all_posts.php'; ?>
<!--                    <div id="outbound_progress_bar" class="lr-w-full lr-bg-gray-200 lr-rounded-full lr-h-2.5 lr-hidden lr-mb-2">-->
<!--                        <div class="bar lr-bg-blue-600 lr-h-2.5 lr-rounded-full lr-transition" style="width: 45%"></div>-->
<!--                    </div>-->
<!--                    <div id="outbound_progress_bar_text" class="lr-w-full lr-text-center"></div>-->
<!---->
<!--                </div>-->

            </div>
        </div>

        <?php

    }
}