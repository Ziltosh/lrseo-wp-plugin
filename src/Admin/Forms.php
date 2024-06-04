<?php

namespace Admin;

use Admin\Tools\Prompts;

class Forms
{
    public static function init()
    {
        add_action('admin_post_inbound_post_select', [__CLASS__, 'handle_inbound_post_select']);
//        self::localize_scripts();
//        add_action('wp_no_priv_ajax_test', [__CLASS__, 'handle_test']);
    }

    /**
     * @throws \Exception
     */
    public static function handle_inbound_post_select()
    {
        if (empty($_POST) || !wp_verify_nonce($_POST['inbound_post_select_nonce'], 'inbound_post_select')) {
            echo 'You targeted the right function, but sorry, your nonce did not verify.';
            wp_die();
        } else {
            $postId = sanitize_key($_POST['post_id']);
            $liste = sanitize_text_field($_POST['liste']);

            $srcPost = get_post(intval($postId));
            $result = Prompts::ScorePostsInbound($srcPost->post_title, $liste);
            // On extrait le json qui est dans les balises <code></code> pour le stocker dans un transient
            preg_match('/<code>(.*?)<\/code>/s', $result, $matches);
            $result = json_decode($matches[1], true);
            set_transient('lrseo_inbound_post_select', $result, 600);
            wp_redirect(add_query_arg('lrseo_inbound_post_select', $postId, sanitize_url($_POST['_wp_http_referer'])));
        }
    }

    public static function localize_scripts()
    {
        // Passer les données nécessaires au script
        wp_localize_script('lrseo_allposts', 'lrseo_allposts', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lrseo_allposts')
        ]);
//        die();
    }
}