<?php
/**
 * Inject Tailwind CSS in <head>
 */

add_action('wp_head', function () {
    if (is_user_logged_in()) return;

    $file_name = tailwind_cache_get_filename();
    $path = get_stylesheet_directory() . '/cache/' . $file_name;

    if (file_exists($path)) {
        $css = file_get_contents($path);
        echo "<style id='tailwind-inline-css-{$file_name}'>\n" . $css . "\n</style>";
    }
    
    // Also load theme-options.css if it exists
    $theme_options_path = get_stylesheet_directory() . '/cache/theme-options.css';
    if (file_exists($theme_options_path)) {
        $theme_css = file_get_contents($theme_options_path);
        echo "<style id='tailwind-inline-css-theme-options'>\n" . $theme_css . "\n</style>";
    }
});

// Get appropriate filename for current page/view
function tailwind_cache_get_filename($type = null, $post_id = null, $post_type = null) {
    // If parameters are provided, use them for specific file naming
    if ($type && $post_id) {
        if ($type === 'page') {
            return 'page-' . $post_id . '.css';
        } elseif ($type === 'posttype' && $post_type) {
            return sanitize_key($post_type) . '.css';
        }
    }
    
    // Fallback to current page detection
    if (is_page()) {
        return 'page-' . get_the_ID() . '.css';
    } elseif (is_singular('post')) {
        return 'post.css';
    } elseif (is_singular()) {
        return get_post_type() . '.css';
    } elseif (is_search()) {
        return 'search.css';
    } elseif (is_404()) {
        return '404.css';
    } elseif (is_archive()) {
        return 'archive.css';
    }
    return 'default.css';
}

// REST API to save Tailwind CSS cache
add_action('rest_api_init', function () {
    register_rest_route('tailwind-cache/v1', '/save', [
        'methods' => 'POST',
        'callback' => 'tailwind_cache_save_css',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ]);
});

function tailwind_cache_save_css($request) {
    $type = sanitize_text_field($request['type']);
    $post_id = intval($request['post_id']);
    $css = trim($request['css']);

    if (empty($css)) {
        return new WP_Error('no_css', 'No CSS provided', ['status' => 400]);
    }

    if ($type === 'page' && $post_id) {
        $file = "page-{$post_id}.css";
    } elseif ($type === 'posttype' && !empty($request['post_type'])) {
        $file = sanitize_key($request['post_type']) . '.css';
    } elseif (in_array($type, ['archive', 'search', '404'])) {
        $file = $type . '.css';
    } elseif ($type === 'theme-options') {
        $file = 'theme-options.css';
    } else {
        return new WP_Error('invalid_type', 'Invalid type or missing post ID/type', ['status' => 400]);
    }

    $dir = get_stylesheet_directory() . '/cache/';
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }

    $path = $dir . $file;
    file_put_contents($path, wp_strip_all_tags($css));

    return rest_ensure_response(['saved' => $file]);
}

// Auto-delete page cache file when the page is deleted
add_action('before_delete_post', function ($post_id) {
    if (get_post_type($post_id) !== 'page') return;

    $file = "page-{$post_id}.css";
    $path = get_stylesheet_directory() . "/cache/{$file}";

    if (file_exists($path)) {
        unlink($path);
    }
});