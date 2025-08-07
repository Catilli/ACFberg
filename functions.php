<?php

// Global text domain - automatically uses theme folder name
$GLOBALS['textdomain'] = get_template();

// Helper function to get text domain (can be used in template files)
function get_text_domain() {
    return $GLOBALS['textdomain'];
}

// Include functions files
require_once get_template_directory() . '/functions/setup.php';           // Theme setup & menus
require_once get_template_directory() . '/functions/script-system.php';   // Script enqueuing
require_once get_template_directory() . '/functions/block-patterns.php';  // Block patterns
require_once get_template_directory() . '/functions/disable-comments.php'; // Disable comments

/**
 * Load ACF-related files after init to prevent translation loading issues
 */
function load_acf_files() {
    require_once get_template_directory() . '/functions/acf-functions.php';   // ACF helper functions
    require_once get_template_directory() . '/functions/options.php';         // ACF options pages
}
add_action('init', 'load_acf_files', 5);

/**
 * Enqueue scripts and styles
 */
function my_enqueue_scripts() {
    // Enqueue theme's main style.css for WordPress theme info
    wp_enqueue_style(
        'style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');