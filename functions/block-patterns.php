<?php
/**
 * Pattern Functions
 * 
 * Functions for reusable patterns throughout the theme
 */

/**
 * Get Block Pattern by Name
 * 
 * Retrieves a block pattern that was created in the Gutenberg editor
 * 
 * @param string $pattern_name The name/slug of the pattern to retrieve
 * @param array $args Optional arguments for customizing the pattern
 * @return string HTML output of the pattern or empty string if not found
 */
function get_block_pattern_by_name($pattern_name, $args = array()) {
    // Get all registered block patterns
    $patterns = WP_Block_Pattern_Registry::get_instance()->get_all_registered();
    
    foreach ($patterns as $pattern) {
        if ($pattern['name'] === $pattern_name || $pattern['slug'] === $pattern_name) {
            return do_blocks($pattern['content']);
        }
    }
    
    return '';
}

/**
 * Check if Block Pattern Exists
 * 
 * @param string $pattern_name The name/slug of the pattern to check
 * @return bool True if pattern exists, false otherwise
 */
function block_pattern_exists($pattern_name) {
    $patterns = WP_Block_Pattern_Registry::get_instance()->get_all_registered();
    
    foreach ($patterns as $pattern) {
        if ($pattern['name'] === $pattern_name || $pattern['slug'] === $pattern_name) {
            return true;
        }
    }
    
    return false;
}

/**
 * Get Block Pattern Info
 * 
 * Retrieves metadata about a specific block pattern
 * 
 * @param string $pattern_name The name/slug of the pattern
 * @return array|false Pattern info array or false if not found
 */
function get_block_pattern_info($pattern_name) {
    $patterns = WP_Block_Pattern_Registry::get_instance()->get_all_registered();
    
    foreach ($patterns as $pattern) {
        if ($pattern['name'] === $pattern_name || $pattern['slug'] === $pattern_name) {
            return $pattern;
        }
    }
    
    return false;
}

/**
 * Shortcode for Gutenberg Block Patterns
 */

/**
 * Shortcode to insert block pattern by name
 * 
 * Usage: [block_pattern name="my-pattern-name"]
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output for the block pattern
 */
function block_pattern_shortcode($atts) {
    $atts = shortcode_atts(array(
        'name' => ''
    ), $atts, 'block_pattern');
    
    if (!empty($atts['name'])) {
        return get_block_pattern_by_name($atts['name']);
    }
    
    return '<p>Pattern not found. Please specify a valid pattern name.</p>';
}
add_shortcode('block_pattern', 'block_pattern_shortcode');

/**
 * Function to get block pattern content by name
 * 
 * @param string $name Pattern name
 * @return string HTML output for the block pattern
 */
function get_block_pattern($name) {
    return get_block_pattern_by_name($name);
} 