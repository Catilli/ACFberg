<?php
/**
 * Dynamic Class System
 * > Inline JSON CSS
 * > Supports both class="" and id=""
 * > Width Media Query Grouping
 * > Skips wp-admin and AJAX/admin screens
 */

add_action('template_redirect', function () {
    // Only run on frontend or Beaver Builder editor
    if (!is_admin() && !defined('DOING_AJAX') && !is_feed() && !is_preview()) {
        ob_start('cs_process_buffer');
    }
});

function cs_process_buffer($html) {
    $json_path = get_stylesheet_directory() . '/assets/style.json';

    if (!file_exists($json_path)) return $html;

    $class_map = json_decode(file_get_contents($json_path), true);
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

    // Match from style.json
    $used_classes = [];
    foreach (array_keys($used_keys) as $key) {
        if (isset($class_map[$key])) {
            $used_classes[$key] = $class_map[$key];
        }
    }

    // Group base and media-query CSS
    $base_css = [];
    $media_queries = [];

    foreach ($used_classes as $css) {
        if (preg_match('/@media\s*\((.*?)\)\s*\{(.*?)\}/s', $css, $media_match)) {
            $breakpoint = trim($media_match[1]);
            $rule_body = trim($media_match[2]);

            if (!isset($media_queries[$breakpoint])) {
                $media_queries[$breakpoint] = [];
            }
            $media_queries[$breakpoint][] = $rule_body;
        } else {
            $base_css[] = $css;
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