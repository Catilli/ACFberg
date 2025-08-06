<?php
/**
 * ACF Options Page
 * Creates theme options page and field groups
 */

/**
 * Add ACF Options Page
 */
function add_my_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'  => 'Theme Options',
            'menu_title'  => 'Theme Options',
            'menu_slug'   => 'theme-options',
            'capability'  => 'edit_posts',
            'redirect'    => false,
            'icon_url'    => 'dashicons-admin-generic',
            'position'    => 59
        ));
        
        // Add sub-pages if needed
        acf_add_options_sub_page(array(
            'page_title'  => 'Header Settings',
            'menu_title'  => 'Header',
            'parent_slug' => 'theme-options',
        ));
    }
}
add_action('acf/init', 'add_my_options_page');

/**
 * Add ACF Field Groups
 */
function add_my_local_field_groups() {
    if (function_exists('acf_add_local_field_group')) {
        
        // Header Settings Field Group
        acf_add_local_field_group(array(
            'key' => 'group_header_settings',
            'title' => 'Header Settings',
            'fields' => array(
                array(
                    'key' => 'field_site_logo',
                    'label' => 'Site Logo',
                    'name' => 'site_logo',
                    'type' => 'image',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-options',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
        ));
    }
}
add_action('acf/init', 'add_my_local_field_groups');

/**
 * Helper function to get ACF options
 */
function get_acf_option($field_name, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, 'option');
        return $value !== null ? $value : $default;
    }
    return $default;
}

/**
 * Helper function to get ACF option with fallback
 */
function acf_option($field_name, $default = '') {
    echo get_acf_option($field_name, $default);
} 