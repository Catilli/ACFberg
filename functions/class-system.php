<?php
/**
 * Simple Tailwind CSS System
 * > Loads CDN for logged-in users
 * > Injects stored CSS for frontend users
 */

/**
 * Get stored CSS for frontend users
 */
function get_stored_tailwind_css() {
    $stored_css = get_option('tailwind_captured_css', '');
    return $stored_css;
}

/**
 * Enqueue Tailwind CDN for logged-in users
 */
function enqueue_tailwind_for_logged_in() {
    if (is_user_logged_in()) {
        wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', array(), null, false);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_tailwind_for_logged_in');

/**
 * Inject stored CSS for non-logged-in users
 */
function inject_stored_css_for_frontend() {
    if (!is_user_logged_in()) {
        $stored_css = get_stored_tailwind_css();
        if (!empty($stored_css)) {
            echo '<style id="tailwind-stored-css">' . $stored_css . '</style>';
        }
    }
}
add_action('wp_head', 'inject_stored_css_for_frontend');

/**
 * Admin notice to show when CSS was last captured
 */
function tailwind_css_admin_notice() {
    if (!is_admin() || !is_user_logged_in()) {
        return;
    }
    
    $last_updated = get_option('tailwind_css_last_updated', 0);
    $stored_css = get_stored_tailwind_css();
    $visited_pages = get_option('tailwind_visited_pages', array());
    
    if ($last_updated > 0 && !empty($stored_css)) {
        $date = date('Y-m-d H:i:s', $last_updated);
        $page_count = count($visited_pages);
        echo '<div class="notice notice-info"><p><strong>Tailwind CSS Status:</strong> Last captured on ' . $date . ' (' . strlen($stored_css) . ' characters, ' . $page_count . ' pages visited)</p></div>';
    } else {
        echo '<div class="notice notice-warning"><p><strong>Tailwind CSS Status:</strong> No CSS has been captured yet. Visit a page with Tailwind classes to capture CSS.</p></div>';
    }
}
add_action('admin_notices', 'tailwind_css_admin_notice');