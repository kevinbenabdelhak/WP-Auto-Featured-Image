<?php 
if (!defined('ABSPATH')) {
    exit;
}


if (!function_exists('media_sideload_image')) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
}