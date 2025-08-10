<?php 
if (!defined('ABSPATH')) {
    exit;
}

function my_auto_featured_image_ajax_handler() {
    check_ajax_referer('my-auto-featured-image-nonce', 'nonce');

    $post_type = sanitize_text_field($_POST['post_type']);

    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => -1,
        'fields'         => 'ids'
    );
    $post_ids = get_posts($args);

    wp_send_json_success(array(
        'total' => count($post_ids),
        'ids'   => $post_ids
    ));
}
add_action('wp_ajax_run_my_auto_featured_image', 'my_auto_featured_image_ajax_handler');


function my_auto_featured_image_process_single() {
    check_ajax_referer('my-auto-featured-image-nonce', 'nonce');
    $post_id = intval($_POST['post_id']);

    if ($post_id) {
        $result = my_auto_featured_image_processor($post_id);
        wp_send_json_success(array('updated' => $result));
    } else {
        wp_send_json_error('Invalid post ID');
    }
}
add_action('wp_ajax_process_my_auto_featured_image', 'my_auto_featured_image_process_single');