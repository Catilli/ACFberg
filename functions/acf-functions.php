<?php
/**
 * ACF Helper Functions
 * Safe functions to work with ACF even when plugin is not fully loaded
 */

/**
 * Safely get ACF option with fallback
 * Checks if ACF is available before calling get_field
 */
function get_acf_option($field_name, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, 'option');
        return $value !== null ? $value : $default;
    }
    return $default;
}

/**
 * Safely get ACF field value with fallback
 * Checks if ACF is available before calling get_field
 */
function get_acf_field($field_name, $post_id = false, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        return $value !== null ? $value : $default;
    }
    return $default;
}

/**
 * Check if ACF is available and active
 */
function acf_is_available() {
    return function_exists('get_field');
}

/**
 * Safely update ACF field
 * Checks if ACF is available before calling update_field
 */
function update_acf_field($field_name, $value, $post_id = false) {
    if (function_exists('update_field')) {
        return update_field($field_name, $value, $post_id);
    }
    return false;
}
