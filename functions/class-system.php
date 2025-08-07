<?php
/**
 * Dynamic Class System
 * > Stores generated CSS for frontend use
 * > Supports both class="" and id="" 
 * > Width Media Query Grouping
 */

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
 * Extract CSS from Tailwind CDN style tag
 */
function extract_tailwind_css_from_head($html) {
    // Look for the Tailwind CDN style tag
    if (preg_match('/<style[^>]*id="__next"[^>]*>(.*?)<\/style>/s', $html, $matches)) {
        return trim($matches[1]);
    }
    
    // Alternative: look for any style tag that might contain Tailwind CSS
    if (preg_match('/<style[^>]*>(.*?)<\/style>/s', $html, $matches)) {
        $css_content = trim($matches[1]);
        // Check if it looks like Tailwind CSS (contains utility classes)
        if (strpos($css_content, '.') !== false && 
            (strpos($css_content, 'display:') !== false || 
             strpos($css_content, 'flex') !== false || 
             strpos($css_content, 'grid') !== false ||
             strpos($css_content, 'margin') !== false ||
             strpos($css_content, 'padding') !== false)) {
            return $css_content;
        }
    }
    
    return '';
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
    
    // Extract CSS from Tailwind CDN style tag
    $extracted_css = extract_tailwind_css_from_head($html);
    
    if (!empty($extracted_css)) {
        // Store the CSS for frontend use (accumulate rather than overwrite)
        $existing_css = get_option('tailwind_captured_css', '');
        
        // Combine existing and new CSS, removing duplicates
        $combined_css = combine_css_without_duplicates($existing_css, $extracted_css);
        
        update_option('tailwind_captured_css', $combined_css);
        update_option('tailwind_css_last_updated', current_time('timestamp'));
        
        // Track which pages have been visited for CSS capture
        $visited_pages = get_option('tailwind_visited_pages', array());
        $current_page = get_permalink();
        if (!in_array($current_page, $visited_pages)) {
            $visited_pages[] = $current_page;
            update_option('tailwind_visited_pages', $visited_pages);
        }
        
        // Add a marker to show CSS was captured
        $capture_notice = "<!-- Tailwind CSS captured: " . strlen($extracted_css) . " characters -->\n";
        $html = preg_replace('/<head[^>]*>/', '$0' . $capture_notice, $html, 1);
    }
    
    // Use stored CSS as fallback
    $stored_css = get_stored_tailwind_css();
    if (!empty($stored_css)) {
        $style_tag = "<style id='fallback-tailwind-css'>\n" . $stored_css . "\n</style>\n";
        $html = preg_replace('/<head[^>]*>/', '$0' . $style_tag, $html, 1);
        return $html;
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