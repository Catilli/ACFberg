<?php
/**
 * ACF Options Page
 * Creates theme options page and field groups
 */

/**
 * Add ACF Options Page
 */
function add_my_options_page() {
    // Add parent menu page using admin_menu
    add_menu_page(
        'Theme Options',           // Page title
        'Theme Options',           // Menu title
        'edit_posts',             // Capability
        'theme-options',          // Menu slug
        'theme_options_page_callback', // Callback function
        'dashicons-admin-generic', // Icon
        59                        // Position
    );
    
    // Add child pages using ACF
    if (function_exists('acf_add_options_sub_page')) {
        acf_add_options_sub_page(array(
            'page_title'  => 'Header Settings',
            'menu_title'  => 'Header',
            'parent_slug' => 'theme-options',
        ));
    }
}
add_action('admin_menu', 'add_my_options_page');

/**
 * Callback function for the parent options page
 */
function theme_options_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p>Welcome to the Theme Options page. Use the sub-menu items to configure different sections of your theme.</p>
        
        <div class="card">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="<?php echo admin_url('admin.php?page=theme-options&subpage=header'); ?>">Header Settings</a></li>
            </ul>
        </div>
    </div>
    <?php
}

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