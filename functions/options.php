<?php
/**
 * Custom Options Pages (Alternative to ACF Options Pages)
 */

/**
 * Add custom admin menu pages
 */
function add_custom_options_pages() {
    // Add main options page
    add_menu_page(
        'Theme Options',           // Page title
        'Theme Options',           // Menu title
        'edit_posts',              // Capability
        'theme-options',           // Menu slug
        'render_theme_options_page', // Callback function
        'dashicons-admin-generic', // Icon
        60                         // Position
    );
    
    // Add sub-page for Tailwind CSS
    add_submenu_page(
        'theme-options',           // Parent slug
        'Tailwind CSS Manager',    // Page title
        'Tailwind CSS',            // Menu title
        'edit_posts',              // Capability
        'tailwind-css-manager',    // Menu slug
        'render_tailwind_css_page' // Callback function
    );
}
add_action('admin_menu', 'add_custom_options_pages');

/**
 * Render the main theme options page
 */
function render_theme_options_page() {
    ?>
    <div class="wrap">
        <h1>Theme Options</h1>
        <p>Welcome to the theme options page. Use the submenu to access specific settings.</p>
        
        <div class="card">
            <h2>Quick Actions</h2>
            <p><a href="<?php echo admin_url('admin.php?page=tailwind-css-manager'); ?>" class="button button-primary">Manage Tailwind CSS</a></p>
        </div>
    </div>
    <?php
}

/**
 * Render the Tailwind CSS manager page
 */
function render_tailwind_css_page() {
    // Handle form submissions
    if (isset($_POST['action']) && $_POST['action'] === 'tailwind_css_action') {
        if (isset($_POST['capture_css'])) {
            update_option('tailwind_force_capture', true);
            echo '<div class="notice notice-success"><p>CSS capture triggered! Refresh the page to see the captured CSS.</p></div>';
        } elseif (isset($_POST['clear_css'])) {
            delete_option('tailwind_captured_css');
            delete_option('tailwind_css_last_updated');
            delete_option('tailwind_visited_pages');
            echo '<div class="notice notice-success"><p>Stored CSS has been cleared.</p></div>';
        }
    }
    
    $last_updated = get_option('tailwind_css_last_updated', 0);
    $stored_css = get_stored_tailwind_css();
    $visited_pages = get_option('tailwind_visited_pages', array());
    ?>
    
    <div class="wrap">
        <h1>Tailwind CSS Manager</h1>
        
        <!-- Status Card -->
        <div class="card">
            <h2>CSS Status</h2>
            <?php if ($last_updated > 0 && !empty($stored_css)): ?>
                <p><strong>✅ CSS captured on:</strong> <?php echo date('Y-m-d H:i:s', $last_updated); ?></p>
                <p><strong>Size:</strong> <?php echo strlen($stored_css); ?> characters</p>
                <p><strong>Pages visited:</strong> <?php echo count($visited_pages); ?></p>
            <?php else: ?>
                <p><strong>⚠️ No CSS captured yet</strong></p>
            <?php endif; ?>
        </div>
        
        <!-- Actions Card -->
        <div class="card">
            <h2>Actions</h2>
            <form method="post">
                <input type="hidden" name="action" value="tailwind_css_action">
                <p>
                    <button type="submit" name="capture_css" class="button button-primary">Capture Current CSS</button>
                    <button type="submit" name="clear_css" class="button button-secondary" onclick="return confirm('Are you sure you want to clear all stored CSS?')">Clear Stored CSS</button>
                </p>
            </form>
        </div>
        
        <!-- CSS Preview Card -->
        <?php if (!empty($stored_css)): ?>
        <div class="card">
            <h2>Stored CSS Preview</h2>
            <textarea readonly style="width: 100%; height: 400px; font-family: monospace; font-size: 12px;"><?php echo esc_textarea($stored_css); ?></textarea>
        </div>
        <?php endif; ?>
        
        <!-- Visited Pages Card -->
        <?php if (!empty($visited_pages)): ?>
        <div class="card">
            <h2>Visited Pages (<?php echo count($visited_pages); ?>)</h2>
            <ul>
                <?php foreach ($visited_pages as $page): ?>
                    <li><?php echo esc_url($page); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Alternative 2: Using WordPress Settings API
 */
function register_theme_settings() {
    register_setting('theme_options_group', 'theme_options');
    
    add_settings_section(
        'theme_options_section',
        'Theme Settings',
        'theme_options_section_callback',
        'theme-options'
    );
    
    add_settings_field(
        'site_logo',
        'Site Logo URL',
        'site_logo_callback',
        'theme-options',
        'theme_options_section'
    );
}
// Uncomment to use Settings API instead
// add_action('admin_init', 'register_theme_settings');

function theme_options_section_callback() {
    echo '<p>Configure your theme settings below:</p>';
}

function site_logo_callback() {
    $options = get_option('theme_options');
    $logo_url = isset($options['site_logo']) ? $options['site_logo'] : '';
    echo '<input type="url" name="theme_options[site_logo]" value="' . esc_attr($logo_url) . '" class="regular-text" />';
    echo '<p class="description">Enter the URL for your site logo</p>';
}

/**
 * Alternative 3: Using Custom Post Types for Options
 */
function create_options_post_type() {
    register_post_type('theme_option', array(
        'labels' => array(
            'name' => 'Theme Options',
            'singular_name' => 'Theme Option'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'theme-options',
        'supports' => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'edit_posts',
            'edit_post' => 'edit_posts',
            'read_post' => 'edit_posts',
            'delete_post' => 'edit_posts'
        )
    ));
}
// Uncomment to use Custom Post Type approach
// add_action('init', 'create_options_post_type');

/**
 * Manual capture action handler
 */
add_action('tailwind_manual_capture', function() {
    // This will be triggered when the capture button is clicked
    // The actual capture happens on the next page load
    update_option('tailwind_force_capture', true);
});