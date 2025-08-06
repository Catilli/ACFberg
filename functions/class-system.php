<?php
/**
 * Dynamic Class System
 * > Inline JSON CSS from Tailwind CDN
 * > Supports both class="" and id="" 
 * > Width Media Query Grouping
 * > Skips wp-admin and AJAX/admin screens
 */

/**
 * Get Tailwind JSON from CDN
 */
function get_tailwind_json() {
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

add_action('template_redirect', function () {
    // Only run on frontend or Beaver Builder editor
    if (!is_admin() && !defined('DOING_AJAX') && !is_feed() && !is_preview()) {
        ob_start('cs_process_buffer');
    }
});

function cs_process_buffer($html) {
    // Get Tailwind JSON from CDN
    $tailwind_json = get_tailwind_json();
    
    if (!$tailwind_json) return $html;

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

    // Inject into <head>
    if (!empty($output)) {
        $style_tag = "<style id='inline-json-styles'>\n" . implode("\n", $output) . "\n</style>\n";
        $html = preg_replace('/<head[^>]*>/', '$0' . $style_tag, $html, 1);
    }

    return $html;
}