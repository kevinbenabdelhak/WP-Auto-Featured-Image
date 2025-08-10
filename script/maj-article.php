<?php 
if (!defined('ABSPATH')) {
    exit;
}
function my_auto_featured_image_on_save($post_id) {

    if (get_option('wp_auto_featured_image_automatic_update_enabled') == 1) {
    
        if (wp_is_post_revision($post_id) || get_post_type($post_id) === 'attachment') {
            return;
        }

        remove_action('save_post', 'my_auto_featured_image_on_save');
        
        my_auto_featured_image_processor($post_id);
        

        add_action('save_post', 'my_auto_featured_image_on_save');
    }
}
add_action('save_post', 'my_auto_featured_image_on_save');
