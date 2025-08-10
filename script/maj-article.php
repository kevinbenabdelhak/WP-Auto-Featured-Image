<?php 
if (!defined('ABSPATH')) {
    exit;
}
function my_auto_featured_image_on_save($post_id) {
    my_auto_featured_image_processor($post_id);
}
add_action('save_post', 'my_auto_featured_image_on_save');