<?php 
if (!defined('ABSPATH')) {
    exit;
}

function my_auto_featured_image_options_page() {
    add_options_page(
        'WP Auto Featured Image',
        'WP Auto Featured Image',
        'manage_options',
        'wp-auto-featured-image-settings',
        'my_auto_featured_image_page_html'
    );
}
add_action('admin_menu', 'my_auto_featured_image_options_page');