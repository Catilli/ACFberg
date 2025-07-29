<?php
/**
 * Disable Comments for Pages Only
 * 
 * This file disables comments functionality for Pages while keeping
 * comments enabled for Posts and other post types.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Disable comments functionality for Pages only
function disable_comments_for_pages() {
    // Remove comments support from pages only
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'trackbacks');
    
    // Close comments on pages only
    add_filter('comments_open', function($open, $post_id) {
        $post_type = get_post_type($post_id);
        if ($post_type === 'page') {
            return false;
        }
        return $open;
    }, 20, 2);
    
    // Close pings on pages only
    add_filter('pings_open', function($open, $post_id) {
        $post_type = get_post_type($post_id);
        if ($post_type === 'page') {
            return false;
        }
        return $open;
    }, 20, 2);
    
    // Hide existing comments on pages only
    add_filter('comments_array', function($comments, $post_id) {
        $post_type = get_post_type($post_id);
        if ($post_type === 'page') {
            return array();
        }
        return $comments;
    }, 10, 2);
    
    // Remove comments column from pages admin only
    add_filter('manage_pages_columns', function($columns) {
        unset($columns['comments']);
        return $columns;
    });
}
add_action('init', 'disable_comments_for_pages');