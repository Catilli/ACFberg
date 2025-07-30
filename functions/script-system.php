<?php
/**
 * Dynamic Script Loader via $.getScript()
 * Loads only used scripts based on class/id in HTML
 */

add_action('template_redirect', function () {
    if (!is_admin() && !defined('DOING_AJAX') && !is_feed() && !is_preview()) {
        ob_start('ss_process_buffer');
    }
});

function ss_process_buffer($html) {
    $json_path = get_stylesheet_directory() . '/assets/script.json';
    $js_base_url = get_stylesheet_directory_uri() . '/assets/js/';

    if (!file_exists($json_path)) return $html;

    $script_map = json_decode(file_get_contents($json_path), true);
    if (!is_array($script_map)) return $html;

    $found_keys = [];

    // Extract class="..."
    preg_match_all('/class=["\']([^"\']+)["\']/', $html, $class_matches);
    if (!empty($class_matches[1])) {
        foreach ($class_matches[1] as $class_string) {
            foreach (preg_split('/\s+/', trim($class_string)) as $class) {
                $found_keys[$class] = true;
            }
        }
    }

    // Extract id="..."
    preg_match_all('/id=["\']([^"\']+)["\']/', $html, $id_matches);
    if (!empty($id_matches[1])) {
        foreach ($id_matches[1] as $id) {
            $found_keys[$id] = true;
        }
    }

    // Build list of JS files to load
    $scripts = [];
    $priority_scripts = [];
    
    foreach (array_keys($found_keys) as $key) {
        if (isset($script_map[$key])) {
            $filename = esc_js($script_map[$key]);
            $script_code = "$.getScript('{$js_base_url}{$filename}');";
            
            // Priority scripts that need to load early
            if (strpos($filename, 'responsive-gfrecaptcha') !== false) {
                $priority_scripts[] = $script_code;
            } else {
                $scripts[] = $script_code;
            }
        }
    }

    // Output jQuery loader script
    if (!empty($priority_scripts) || !empty($scripts)) {
        $js = "<script id='inline-json-script-loader'>\n";
        
        // Load priority scripts immediately
        if (!empty($priority_scripts)) {
            $js .= "jQuery(function($) {\n";
            $js .= implode("\n", $priority_scripts);
            $js .= "\n});\n";
        }
        
        // Load other scripts after DOM is ready
        if (!empty($scripts)) {
            $js .= "jQuery(function($) {\n";
            $js .= implode("\n", $scripts);
            $js .= "\n});\n";
        }
        
        $js .= "</script>\n";

        $html = preg_replace('/<\/body>/i', $js . '</body>', $html, 1);
    }

    return $html;
} 