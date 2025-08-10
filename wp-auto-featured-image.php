<?php
/*
Plugin Name: WP Auto Featured Image
Plugin URI: https://kevin-benabdelhak.fr/plugins/wp-auto-featured-image/
Description: WP Auto Featured Image est un plugin WordPress qui définit automatiquement la première image trouvée dans le contenu d'un article comme image mise en avant, si l'article n'en a pas déjà une. Le plugin agit soit de manière instantanée lors de l'enregistrement ou de la mise à jour d'un article, soit de manière manuelle pour l'ensemble d'un type de contenu via sa page de réglages.
Version: 1.1
Author: Kevin BENABDELHAK
Author URI: https://kevin-benabdelhak.fr/
Contributors: kevinbenabdelhak
*/

if (!defined('ABSPATH')) {
    exit;
}

if ( !class_exists( 'YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
    require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
}
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$monUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/kevinbenabdelhak/WP-Auto-Featured-Image/', 
    __FILE__,
    'wp-auto-featured-image' 
);
$monUpdateChecker->setBranch('main');



add_action('admin_init', 'my_auto_featured_image_register_settings');
function my_auto_featured_image_register_settings() {
    register_setting( 'wp_auto_featured_image_options_group', 'wp_auto_featured_image_automatic_update_enabled' );
}


require_once plugin_dir_path(__FILE__) . 'script/add-media.php';
require_once plugin_dir_path(__FILE__) . 'script/search-image.php';
require_once plugin_dir_path(__FILE__) . 'script/maj-article.php';
require_once plugin_dir_path(__FILE__) . 'options/add-options.php';
require_once plugin_dir_path(__FILE__) . 'options/settings.php';
require_once plugin_dir_path(__FILE__) . 'script/add-media.php';
require_once plugin_dir_path(__FILE__) . 'script/ajax.php';


