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


add_shortcode('lrseo', [Shortcodes::class, 'lrseo_shortcode']);

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