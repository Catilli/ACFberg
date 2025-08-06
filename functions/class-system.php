<?php
/**
 * Dynamic Class System
 * > Admin-only Tailwind CDN with CSS capture
 * > Stores generated CSS for frontend use
 * > Supports both class="" and id="" 
 * > Width Media Query Grouping
 */

/**
 * Get Tailwind JSON from CDN (admin only)
 */
function get_tailwind_json() {
    // Only run in admin or when user is logged in
    if (!is_admin() && !is_user_logged_in()) {
        return false;
    }
    
    // Cache the JSON for 1 hour to avoid repeated requests
    $cache_key = 'tailwind_json_cache';
    $cached_json = get_transient($cache_key);
    
    if ($cached_json !== false) {
        return $cached_json;
    }
    
    // Fetch JSON from Tailwind CDN
    $response = wp_remote_get('https://cdn.tailwindcss.com/3.4.1', array(
        'timeout' => 10,
        'headers' => array(
            'Accept' => 'application/json'
        )
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    
    // Extract JSON from the JavaScript response
    if (preg_match('/window\["tailwind"\]\s*=\s*({.*?});/s', $body, $matches)) {
        $json_string = $matches[1];
        $json_data = json_decode($json_string, true);
        
        if ($json_data && isset($json_data['css'])) {
            // Cache for 1 hour
            set_transient($cache_key, $json_data, HOUR_IN_SECONDS);
            return $json_data;
        }
    }
    
    return false;
}

/**
 * Get stored CSS for frontend users
 */
function get_stored_tailwind_css() {
    $stored_css = get_option('tailwind_captured_css', '');
    return $stored_css;
}

/**
 * Combine CSS without duplicates
 */
function combine_css_without_duplicates($existing_css, $new_css) {
    // Extract all CSS rules from existing CSS
    $existing_rules = [];
    preg_match_all('/\.([^{]+)\s*\{([^}]+)\}/s', $existing_css, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $selector = trim($match[1]);
        $properties = trim($match[2]);
        $existing_rules[$selector] = $properties;
    }
    
    // Extract all CSS rules from new CSS
    $new_rules = [];
    preg_match_all('/\.([^{]+)\s*\{([^}]+)\}/s', $new_css, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $selector = trim($match[1]);
        $properties = trim($match[2]);
        $new_rules[$selector] = $properties;
    }
    
    // Merge rules (new rules override existing ones)
    $combined_rules = array_merge($existing_rules, $new_rules);
    
    // Rebuild CSS
    $combined_css = '';
    foreach ($combined_rules as $selector => $properties) {
        $combined_css .= ".$selector {\n  $properties\n}\n";
    }
    
    return $combined_css;
}

/**
 * Capture and store CSS from admin
 */
function capture_tailwind_css($html) {
    // Only capture in admin or when user is logged in
    if (!is_admin() && !is_user_logged_in()) {
        return $html;
    }
    
    // Check if manual capture was triggered
    $force_capture = get_option('tailwind_force_capture', false);
    if ($force_capture) {
        delete_option('tailwind_force_capture');
    }
    
    // Get Tailwind JSON from CDN
    $tailwind_json = get_tailwind_json();
    
    // If CDN fails, use stored CSS as fallback
    if (!$tailwind_json) {
        $stored_css = get_stored_tailwind_css();
        if (!empty($stored_css)) {
            $style_tag = "<style id='fallback-tailwind-css'>\n" . $stored_css . "\n</style>\n";
            $html = preg_replace('/<head[^>]*>/', '$0' . $style_tag, $html, 1);
            return $html;
        }
        return $html;
    }

    $class_map = $tailwind_json;
    if (!is_array($class_map)) return $html;

    // Extract classes from HTML
    $used_keys = [];

    // Match all class="..." attributes
    preg_match_all('/class=["\']([^"\']+)["\']/', $html, $class_matches);
    if (!empty($class_matches[1])) {
        foreach ($class_matches[1] as $class_string) {
            $classes = preg_split('/\s+/', trim($class_string));
            foreach ($classes as $class) {
                $used_keys[$class] = true;
            }
        }
    }

    // Match all id="..." attributes
    preg_match_all('/id=["\']([^"\']+)["\']/', $html, $id_matches);
    if (!empty($id_matches[1])) {
        foreach ($id_matches[1] as $id) {
            $used_keys[$id] = true;
        }
    }

    // Match from Tailwind JSON
    $used_classes = [];
    foreach (array_keys($used_keys) as $key) {
        if (isset($class_map['css'][$key])) {
            $used_classes[$key] = $class_map['css'][$key];
        }
    }

    // Group base and media-query CSS
    $base_css = [];
    $media_queries = [];

    // Process base CSS
    foreach ($used_classes as $class => $styles) {
        $css_rules = [];
        foreach ($styles as $property => $value) {
            $css_rules[] = "$property: $value;";
        }
        $base_css[] = ".$class {\n  " . implode("\n  ", $css_rules) . "\n}";
    }

    // Process media queries
    if (isset($class_map['media'])) {
        foreach ($class_map['media'] as $breakpoint => $rules) {
            if (!isset($media_queries[$breakpoint])) {
                $media_queries[$breakpoint] = [];
            }
            
            foreach ($rules as $class => $styles) {
                if (isset($used_keys[$class])) {
                    $css_rules = [];
                    foreach ($styles as $property => $value) {
                        $css_rules[] = "$property: $value;";
                    }
                    $media_queries[$breakpoint][] = ".$class {\n    " . implode("\n    ", $css_rules) . "\n  }";
                }
            }
        }
    }

    // Build final CSS
    $output = [];
    if (!empty($base_css)) {
        $output[] = implode("\n", $base_css);
    }

    foreach ($media_queries as $breakpoint => $rules) {
        $output[] = "@media ($breakpoint) {\n" . implode("\n", $rules) . "\n}";
    }

    $generated_css = implode("\n", $output);
    
    // Store the CSS for frontend use (accumulate rather than overwrite)
    if (!empty($generated_css)) {
        $existing_css = get_option('tailwind_captured_css', '');
        
        // Combine existing and new CSS, removing duplicates
        $combined_css = combine_css_without_duplicates($existing_css, $generated_css);
        
        update_option('tailwind_captured_css', $combined_css);
        update_option('tailwind_css_last_updated', current_time('timestamp'));
        
        // Track which pages have been visited for CSS capture
        $visited_pages = get_option('tailwind_visited_pages', array());
        $current_page = get_permalink();
        if (!in_array($current_page, $visited_pages)) {
            $visited_pages[] = $current_page;
            update_option('tailwind_visited_pages', $visited_pages);
        }
    }

    // Inject into <head> for admin users
    if (!empty($output)) {
        $style_tag = "<style id='inline-json-styles'>\n" . implode("\n", $output) . "\n</style>\n";
        $html = preg_replace('/<head[^>]*>/', '$0' . $style_tag, $html, 1);
    }

    return $html;
}

/**
 * Inject stored CSS for frontend users
 */
function inject_stored_css($html) {
    // Only inject for non-admin, non-logged-in users
    if (is_admin() || is_user_logged_in()) {
        return $html;
    }
    
    $stored_css = get_stored_tailwind_css();
    
    if (!empty($stored_css)) {
        $style_tag = "<style id='stored-tailwind-css'>\n" . $stored_css . "\n</style>\n";
        $html = preg_replace('/<head[^>]*>/', '$0' . $style_tag, $html, 1);
    }

    return $html;
}

add_action('template_redirect', function () {
    // Only run on frontend
    if (!is_admin() && !defined('DOING_AJAX') && !is_feed() && !is_preview()) {
        ob_start('cs_process_buffer');
    }
});

function cs_process_buffer($html) {
    // If user is logged in or in admin, capture CSS
    if (is_admin() || is_user_logged_in()) {
        return capture_tailwind_css($html);
    }
    
    // Otherwise, inject stored CSS
    return inject_stored_css($html);
}

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