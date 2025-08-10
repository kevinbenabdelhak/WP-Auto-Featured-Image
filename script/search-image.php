<?php 
if (!defined('ABSPATH')) {
    exit;
}

function my_auto_featured_image_processor($post_id) {
    if (has_post_thumbnail($post_id)) {
        return false;
    }

    $post = get_post($post_id);
    if (!$post) {
        return false;
    }
    
    $content = $post->post_content;
    preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);

    if (isset($matches[1])) {
        $image_url = $matches[1];
  
        $filename = basename(parse_url($image_url, PHP_URL_PATH));
        
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT p.ID FROM {$wpdb->posts} AS p INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id WHERE p.post_type = 'attachment' AND pm.meta_key = '_wp_attached_file' AND pm.meta_value LIKE %s LIMIT 1",
            '%/' . $filename
        );
        $attachment_id = $wpdb->get_var($query);
        
        if ($attachment_id) {
            set_post_thumbnail($post_id, $attachment_id);
            return true;
        }

      
        $image_id = media_sideload_image($image_url, $post_id, null, 'id');
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
            return true;
        }
    }
    return false;
}