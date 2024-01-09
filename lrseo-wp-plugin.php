<?php

/*
Plugin Name: LRSEO Plugin
Plugin URI: https://leader-referencement.com
Description: Plugin pour gérer quelques éléments des sites.
Version: 1.0.1
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
    // Ajouter le style
    wp_enqueue_style('lrseo-style', plugin_dir_url(__FILE__) . 'css/lrseo-wp-plugin.css', [], '1.0.0', 'all');

    // Ajouter le script
//    wp_enqueue_script('lrseo-script', plugin_dir_url(__FILE__) . 'js/lrseo.js', [], '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'lrseo_enqueue_style');

add_shortcode('lrseo', [Shortcodes::class, 'lrseo_fieldset']);
add_shortcode('lrseo_fieldset', [Shortcodes::class, 'lrseo_fieldset']);
add_shortcode('lrseo_list', [Shortcodes::class, 'lrseo_list']);
add_shortcode('lrseo_icon', [Shortcodes::class, 'lrseo_icon']);

//----------------------------------

require 'plugin-update-checker-5.3/plugin-update-checker.php';

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