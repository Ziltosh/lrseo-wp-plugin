<?php

namespace Admin;

use Admin\Tools\Prompts;

class Ajax
{
    public static function init()
    {
        add_action('wp_ajax_lrseo_allposts', [__CLASS__, 'handle_allposts']);
        add_action('wp_ajax_lrseo_inbound_select_post', [__CLASS__, 'handle_inbound_select_post']);
        add_action('wp_ajax_lrseo_inbound_analyse_post', [__CLASS__, 'handle_inbound_analyse_post']);
//        add_action();
//        self::localize_scripts();
//        add_action('wp_no_priv_ajax_test', [__CLASS__, 'handle_test']);
    }

    public static function handle_allposts()
    {
        // Vérifier le nonce pour la sécurité
        check_ajax_referer('lrseo_allposts', 'security');

        $posts = get_posts([
            'post_type' => 'post',
            'numberposts' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
        ]);

        $postsWithLinks = Links::processLinks($posts);

        $keepedData = [
            'ID',
            'post_title',
            'outbound_links',
            'inbound_links',
        ];

        $postsWithLinks = array_map(function ($post) use ($keepedData) {
            return array_filter((array)$post, function ($key) use ($keepedData) {
                return in_array($key, $keepedData);
            }, ARRAY_FILTER_USE_KEY);
        }, $postsWithLinks);

        wp_send_json_success($postsWithLinks);
    }

    public static function handle_inbound_select_post()
    {
        // Vérifier le nonce pour la sécurité
        check_ajax_referer('lrseo_inbound_select_post', 'security');

        $kw = sanitize_text_field($_POST['kw']);
        $current = sanitize_key($_POST['current']);
        $step = sanitize_key($_POST['step']);
        $postId = sanitize_key($_POST['post_id']);

//        $liste = sanitize_text_field($_POST['liste']);
        $posts = get_posts([
            'post_type' => 'post',
            'offset' => $current,
            'numberposts' => $step,
            'post_status' => 'publish',
            'orderby' => 'date',
            'exclude' => [$postId],
        ]);

        // On créer la liste de titre sous forme de chaine de caractères séparés par des sauts de ligne
        $liste = '';
        foreach ($posts as $post) {
            $liste .= json_encode(['title' => $post->post_title, 'id' => $post->ID]) . "\n";
        }

        $tries = 1;
        $errors = [];
        while ($tries < 5) {
            try {
                $result = Prompts::ScorePostsInbound($kw, $liste);
                // On extrait le json qui est dans les balises <code></code> pour le stocker dans un transient
                preg_match('/<code>(.*?)<\/code>/s', $result, $matches);
                $result = json_decode($matches[1], true);

                $tries = 5;

                wp_send_json_success($result);
            } catch (\Exception $e) {
                $tries++;
                $errors[] = $e->getMessage();
//            wp_send_json_error($e->getMessage());
//            wp_send_json_error($e->getMessage());
            }
        }

        wp_send_json_error($errors);
    }

    public static function handle_inbound_analyse_post()
    {
        // Vérifier le nonce pour la sécurité
        check_ajax_referer('lrseo_inbound_analyse_post', 'security');

        $postIdSrc = sanitize_key($_POST['post_id_src']);
        $srcPost = get_post(intval($postIdSrc));

        $postIdDst = sanitize_key($_POST['post_id_dst']);
        $dstPost = get_post(intval($postIdDst));

        $kw = sanitize_text_field($_POST['kw']);

        $content = $dstPost->post_content;

        $tries = 1;
        $errors = [];
        while ($tries < 5) {
            try {
                $result = Prompts::InsertLinkInText($kw, get_permalink($srcPost), $content);
                // On extrait le json qui est dans les balises <code></code> pour le stocker dans un transient
                preg_match('/<code>(.*?)<\/code>/s', $result, $matches);
                $result = json_decode($matches[1], true);
                $result['title_dst'] = $dstPost->post_title;
                $result['id_dst'] = $dstPost->ID;

                $tries = 5;

                wp_send_json_success($result);
            } catch (\Exception $e) {
                $tries++;
                $errors[] = $e->getMessage();
            }
        }

        wp_send_json_error($errors);
    }

    public static function localize_scripts()
    {
        // Passer les données nécessaires au script
        wp_localize_script('lrseo_allposts', 'lrseo_allposts', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lrseo_allposts')
        ]);

        wp_localize_script('lrseo_inbound_select_post', 'lrseo_inbound_select_post', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lrseo_inbound_select_post')
        ]);

        wp_localize_script('lrseo_inbound_analyse_post', 'lrseo_inbound_analyse_post', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lrseo_inbound_analyse_post')
        ]);
//        die();
    }
}