<?php

/*
Plugin Name: LRSEO Plugin
Plugin URI: https://leader-referencement.com
Description: Plugin pour gérer quelques éléments des sites.
Version: 2.1.0
Author: Nicolas Egermann
Author URI: https://rfpsb.fr
License: GPLv2
    Copyright 2024 Nicolas Egermann
*/

// Vérifiez que le script n'est pas exécuté en dehors de WordPress.
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use Admin\Ajax;
use Kucrut\Vite;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;


add_action('wp_enqueue_scripts', function (): void {
    Vite\enqueue_asset(
        __DIR__ . '/build',
        'src/js/front_main.ts',
        [
            'handle' => 'front_main',
            'dependencies' => [], // Optional script dependencies. Defaults to empty array.
            'css-dependencies' => [], // Optional style dependencies. Defaults to empty array.
            'css-media' => 'all', // Optional.
            'css-only' => false, // Optional. Set to true to only load style assets in production mode.
            'in-footer' => true, // Optional. Defaults to false.
        ]
    );
});

if (!is_admin()) {
    add_action('plugins_loaded', ['Front\LrseoFront', 'init']);
}

if (is_admin()) {
    add_action('admin_enqueue_scripts', function () {
        Vite\enqueue_asset(
            __DIR__ . '/build',
            'src/js/admin_main.ts',
            [
                'handle' => 'admin_main',
                'dependencies' => ['jquery'], // Optional script dependencies. Defaults to empty array.
                'css-dependencies' => [], // Optional style dependencies. Defaults to empty array.
                'css-media' => 'all', // Optional.
                'css-only' => false, // Optional. Set to true to only load style assets in production mode.
                'in-footer' => true, // Optional. Defaults to false.
            ]
        );

        Vite\enqueue_asset(
            __DIR__ . '/build',
            'src/js/admin.ajax.allposts.js',
            [
                'handle' => 'lrseo_allposts',
                'dependencies' => ['jquery'], // Optional script dependencies. Defaults to empty array.
                'css-dependencies' => [], // Optional style dependencies. Defaults to empty array.
                'css-media' => 'all', // Optional.
                'css-only' => false, // Optional. Set to true to only load style assets in production mode.
                'in-footer' => true, // Optional. Defaults to false.
            ]
        );

        Vite\enqueue_asset(
            __DIR__ . '/build',
            'src/js/admin.ajax.inbound_select_post.js',
            [
                'handle' => 'lrseo_inbound_select_post',
                'dependencies' => ['jquery'],
                'in-footer' => true,
            ]
        );

        Vite\enqueue_asset(
            __DIR__ . '/build',
            'src/js/admin.ajax.inbound_analyse_post.js',
            [
                'handle' => 'lrseo_inbound_analyse_post',
                'dependencies' => ['jquery'],
                'in-footer' => true,
            ]
        );

        Ajax::localize_scripts();

    });



    add_action('plugins_loaded', ['Admin\LrseoAdmin', 'init']);

    //----------------------------------

    require 'plugin-update-checker-5.4/plugin-update-checker.php';

    $myUpdateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/Ziltosh/lrseo-wp-plugin',
        __FILE__,
        'lrseo-wp-plugin'
    );

    //Set the branch that contains the stable release.
    $myUpdateChecker->setBranch('main');
    //$myUpdateChecker->getVcsApi()->enableReleaseAssets();

    //Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('your-token-here');
}

//----------------------------------

