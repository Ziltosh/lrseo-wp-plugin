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
        // self::localize_scripts();
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
            'pct_links',
            'words'
        ];

        $postsWithLinks = array_map(function ($post) use ($keepedData) {
            return array_filter((array) $post, function ($key) use ($keepedData) {
                return in_array($key, $keepedData);
            }, ARRAY_FILTER_USE_KEY);
        }, $postsWithLinks);

        wp_send_json_success($postsWithLinks);
    }

    public static function handle_inbound_select_post()
    {
        // Vérifier le nonce pour la sécurité
        check_ajax_referer('lrseo_inbound_select_post', 'security');

        $title = sanitize_text_field($_POST['title']);
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

        $posts = Links::processLinks($posts);

        // On créer la liste de titre sous forme de chaine de caractères séparés par des sauts de ligne
        $liste = '';
        foreach ($posts as $post) {
            $liste .= json_encode(['title' => $post->post_title, 'id' => $post->ID, 'pct_links' => $post->pct_links]) . "\n";
        }

        $tries = 1;
        $errors = [];
        while ($tries < 5) {
            try {
                $result = Prompts::ScorePostsInbound($title, $kw, $liste);
                // On extrait le json qui se trouve entouré de balises <code></code>
                preg_match('/<code>(.*?)<\/code>/s', $result, $matches);
                // On vérifie qu'il y a bien un match, sinon on decode le texte brut
                if (isset($matches[1])) {
                    $matches[1] = str_replace("\n", '', $matches[1]);
                    $jsonResult = json_decode($matches[1], true);
                } else {
                    $result = str_replace("\n", '', $result);
                    $jsonResult = json_decode($result, true);
                }

                //                var_dump($matches[1]);
//                var_dump($result);
//                var_dump($jsonResult);
//                die();

                if ($jsonResult === null) {
                    throw new \Exception($jsonResult['error']);
                }

                $tries = 5;

                wp_send_json_success($jsonResult);
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
        $nbResultsVoulus = 3;

        $postIdSrc = sanitize_key($_POST['post_id_src']);
        $srcPost = get_post(intval($postIdSrc));

        $postIdDst = sanitize_key($_POST['post_id_dst']);
        $dstPost = get_post(intval($postIdDst));

        $kw = sanitize_text_field($_POST['kw']);
        $titre = $srcPost->post_title;

        $content = $dstPost->post_content;

        $tries = 1;
        $errors = [];
        $results = [];
        while ($tries < 5 && count($results) < $nbResultsVoulus) {
            try {
                $result = Prompts::InsertLinkInText($kw, $titre, get_permalink($srcPost), $content);
                // On extrait le json qui est dans les balises <code></code> pour le stocker dans un transient
                // On extrait le json qui se trouve entouré de balises <code></code>
                preg_match('/<code>(.*?)<\/code>/s', $result, $matches);
                // On vérifie qu'il y a bien un match, sinon on decode le texte brut
                if (isset($matches[1])) {
                    $matches[1] = str_replace("\n", '', $matches[1]);
                    $jsonResult = json_decode($matches[1], true);
                } else {
                    $result = str_replace("\n", '', $result);
                    $jsonResult = json_decode($result, true);
                }

                $jsonResult['title_dst'] = $dstPost->post_title;
                $jsonResult['id_dst'] = $dstPost->ID;

                $results[] = $jsonResult;
                $tries = 1;

                if (count($results) >= $nbResultsVoulus) {
                    $tries = 5;
                    wp_send_json_success($results);
                }
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
    }
}