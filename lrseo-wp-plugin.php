<?php
/*
Plugin Name: LRSEO Plugin
Plugin URI: https://leader-referencement.com
Description: Plugin pour gérer quelques éléments des sites.
Version: 2.0.0
Author: Nicolas Egermann
Author URI: https://rfpsb.fr
License: GPLv2
    Copyright 2024 Nicolas Egermann
*/

// Vérifiez que le script n'est pas exécuté en dehors de WordPress.
if (!defined('ABSPATH')) {
    exit;
}

//----------------------------------

require_once 'vendor/autoload.php';

use Admin\Ajax;
use ViteHelpers\Assets;
use ViteHelpers\DevServer;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

Assets::register([
    'dir' => plugin_dir_path(__FILE__), // or get_stylesheet_directory() for themes
    'url' => plugins_url(\basename(__DIR__)), // or get_stylesheet_directory_uri() for themes
]);


add_action('wp_enqueue_scripts', function () {
    Assets::font("lrseo.woff2");
    Assets::font("lrseo.woff");
    Assets::font("lrseo.ttf");
    wp_enqueue_style('front_base', Assets::css('front_base.pcss'));
    wp_enqueue_script('front_main', Assets::js('front_main.js'));
}, 2);

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('admin_base', Assets::css('admin_base.pcss'));
//    if (function_exists('wp_enqueue_script_module')) {
//        wp_register_script_module('admin_main', Assets::js('admin_main'));
//        wp_enqueue_script_module('admin_main', Assets::js('admin_main'), []);
//        wp_enqueue_script_module('lrseo_allposts', Assets::js('admin.ajax.allposts'), []);
//        wp_enqueue_script_module('lrseo_inbound_select_post', Assets::js('admin.ajax.inbound_select_post'), []);
//        wp_enqueue_script_module('lrseo_inbound_analyse_post', Assets::js('admin.ajax.inbound_analyse_post'), []);
//    } else {
        wp_enqueue_script('admin_main', Assets::js('admin_main.js'), [], null, false);
        wp_enqueue_script('lrseo_allposts', Assets::js('admin.ajax.allposts.js'), [], null, false);
        wp_enqueue_script('lrseo_inbound_select_post', Assets::js('admin.ajax.inbound_select_post.js'), [], null, false);
        wp_enqueue_script('lrseo_inbound_analyse_post', Assets::js('admin.ajax.inbound_analyse_post.js'), [], null, false);
        // On filtre avec script_loader_tag pour ajouter l'attribut type="module" aux scripts qui en ont besoin
    add_filter('script_loader_tag', function ($tag, $handle) {
        if (in_array($handle, ['admin_main', 'lrseo_allposts', 'lrseo_inbound_select_post', 'lrseo_inbound_analyse_post'])) {
            return str_replace(' src', ' type="module" src', $tag);
        }
        return $tag;
    }, 10, 2);
//    }

    Ajax::localize_scripts();
}, 1);

// Initialiser le plugin
add_action('plugins_loaded', ['Front\LrseoFront', 'init']);
add_action('plugins_loaded', ['Admin\LrseoAdmin', 'init']);
//add_action('wp_enqueue_scripts', ['LRSEO\Lrseo', 'front_enqueue_scripts']);

// Listens to ViteJS dev server and makes adjustment to make HMR work
//if (defined('WP_DEBUG') && WP_DEBUG === true) {
add_action('init', function () {
    $assets = Assets::register([
        'dir' => plugin_dir_path(__FILE__), // or get_stylesheet_directory() for themes
        'url' => plugins_url(\basename(__DIR__)), // or get_stylesheet_directory_uri() for themes
    ]);
    $devServer = new DevServer($assets);
    $devServer->start("3000");
});
//}

//----------------------------------

require 'plugin-update-checker-5.4/plugin-update-checker.php';

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Ziltosh/lrseo-wp-plugin',
    __FILE__,
    'lrseo-wp-plugin'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('release');
$myUpdateChecker->getVcsApi()->enableReleaseAssets();

//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('your-token-here');