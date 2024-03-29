<?php

/*
Plugin Name: LRSEO Plugin
Plugin URI: https://leader-referencement.com
Description: Plugin pour gérer quelques éléments des sites.
Version: 1.0.5
Author: Nicolas Egermann
Author URI: https://rfpsb.fr
License: GPLv2
    Copyright 2024 Nicolas Egermann
*/

// Vérifiez que le script n'est pas exécuté en dehors de WordPress.
if (!defined('ABSPATH')) {
    exit;
}

include_once plugin_dir_path(__FILE__) . 'Shortcodes.php';

//----------------------------------

// Ajouter les styles et scripts
function lrseo_enqueue_style()
{
    /***************************************************************** MODIFIER ICI ENTRE CHAQUE VERSION */
    wp_register_style('lrseo-wp-plugin', plugin_dir_url(__FILE__) . 'lrseo-wp-plugin.css', [], '1.0.5', 'all');
    wp_register_style('lrseo-font', plugin_dir_url(__FILE__) . 'lrseo-font.css', [], '1.0.5', 'all');

    // Ajouter le style
    wp_enqueue_style('lrseo-wp-plugin');
    wp_enqueue_style('lrseo-font');

    // Ajouter le script
//    wp_enqueue_script('lrseo-script', plugin_dir_url(__FILE__) . 'js/lrseo.js', [], '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'lrseo_enqueue_style');
add_action('wp_head', function() {
    echo base64_decode(get_post_meta(get_the_ID(), 'lrseo_faq_json', true));
});

//----------------------------------

add_shortcode('lrseo', [Shortcodes::class, 'lrseo_fieldset']);
add_shortcode('lrseo_fieldset', [Shortcodes::class, 'lrseo_fieldset']);
add_shortcode('lrseo_list', [Shortcodes::class, 'lrseo_list']);
add_shortcode('lrseo_icon', [Shortcodes::class, 'lrseo_icon']);
add_shortcode('lrseo_faq', [Shortcodes::class, 'lrseo_faq']);

//----------------------------------


//----------------------------------

require 'plugin-update-checker-5.4/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Ziltosh/lrseo-wp-plugin',
    __FILE__,
    'lrseo-wp-plugin'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('your-token-here');