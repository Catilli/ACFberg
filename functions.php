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
require_once get_template_directory() . '/functions/css-cache.php';

/**
 * Load ACF-related files after init to prevent translation loading issues
 */
function load_acf_files() {
    require_once get_template_directory() . '/functions/acf-functions.php';   // ACF helper functions
    require_once get_template_directory() . '/functions/options.php';         // ACF options pages
}
add_action('init', 'load_acf_files', 5);

add_action('admin_enqueue_scripts', function () {
    wp_localize_script('jquery', 'wpApiSettings', [
        'nonce' => wp_create_nonce('wp_rest')
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('tailwind-cache/v1', '/save', [
        'methods'  => 'POST',
        'callback' => 'tailwind_cache_save',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ]);
});

function tailwind_cache_save($request) {
    $css = $request->get_param('css');
    $type = $request->get_param('type');
    $post_id = (int) $request->get_param('post_id');
    $post_type = sanitize_key($request->get_param('post_type'));

    // Save logic here: you can reuse your existing file save function
    $filename = tailwind_cache_get_filename($type, $post_id, $post_type);
    $path = get_stylesheet_directory() . '/cache/' . $filename;

    if (!file_exists(dirname($path))) {
        wp_mkdir_p(dirname($path));
    }

    file_put_contents($path, $css);

    return new WP_REST_Response(['success' => true, 'file' => $filename], 200);
}