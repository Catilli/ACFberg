<?php
/**
 * Custom Options Pages
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
        <hr>
        <h2>Theme CSS Regeneration</h2>
        <p>This will extract Tailwind CDN styles from the current page and save them as a cache file for frontend use.</p>
        <button id="regenerate-btn" class="button button-primary">Regenerate CSS</button>
    </div>

    <script>
    document.getElementById('regenerate-btn').addEventListener('click', async () => {
        const tailwindStyle = [...document.querySelectorAll('style')]
            .find(s => s.innerText.includes('--tw'));

        if (!tailwindStyle) {
            alert("Tailwind CDN <style> block not found on this admin page.");
            return;
        }

        const css = tailwindStyle.innerText;

        const res = await fetch('/wp-json/tailwind-cache/v1/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': (typeof wpApiSettings !== 'undefined') ? wpApiSettings.nonce : ''
            },
            body: JSON.stringify({
                type: 'theme-options',
                post_id: 0,
                post_type: '',
                css
            })
        });

        if (res.ok) {
            alert("Tailwind CSS successfully regenerated!");
        } else {
            alert("Failed to regenerate Tailwind CSS.");
        }
    });
    </script>
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

