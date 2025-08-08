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
        <p>This will capture Tailwind CSS from the frontend and save it for logged-out users.</p>
        
        <div class="notice notice-info">
            <h3>CSS Regeneration</h3>
            <p>Capture Tailwind CSS from the frontend for logged-out users:</p>
            
            <div class="card">
                <h4>Capture from Frontend</h4>
                <p>Visit your frontend while logged in, then capture the Tailwind CSS.</p>
                <div class="button-group">
                    <a href="<?php echo home_url(); ?>" target="_blank" class="button button-primary">Visit Frontend</a>
                    <button id="capture-frontend-btn" class="button button-secondary">Capture CSS</button>
                    <button id="clear-storage-btn" class="button">Clear Storage</button>
                </div>
                <div id="storage-status" class="description"></div>
            </div>
        </div>
        
        <div id="regeneration-status" class="hidden">
            <div class="notice notice-info">
                <p><strong>Status:</strong> <span id="status-text">Processing...</span></p>
            </div>
        </div>
    </div>

    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin: 20px 0;
    }
    .card h4 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: 600;
    }
    .card p {
        margin-bottom: 15px;
        color: #646970;
    }
    .button-group {
        margin-bottom: 15px;
    }
    .button-group .button {
        margin-right: 10px;
    }
    .description {
        font-style: italic;
        color: #646970;
    }
    </style>

    <script>
    // Function to show status
    function showStatus(message, type = 'info') {
        const statusDiv = document.getElementById('regeneration-status');
        const statusText = document.getElementById('status-text');
        statusDiv.className = `notice notice-${type}`;
        statusText.textContent = message;
        statusDiv.classList.remove('hidden');
    }
    
    // Function to hide status
    function hideStatus() {
        document.getElementById('regeneration-status').classList.add('hidden');
    }
    
    // Function to save CSS
    async function saveCSS(css, type = 'theme-options') {
        try {
            const res = await fetch('/wp-json/tailwind-cache/v1/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': (typeof wpApiSettings !== 'undefined') ? wpApiSettings.nonce : ''
                },
                body: JSON.stringify({
                    type: type,
                    post_id: 0,
                    post_type: '',
                    css: css
                })
            });

            if (res.ok) {
                const result = await res.json();
                showStatus(`CSS successfully saved as: ${result.saved}`, 'success');
                return true;
            } else {
                const error = await res.json();
                showStatus(`Failed to save CSS: ${error.message || 'Unknown error'}`, 'error');
                return false;
            }
        } catch (error) {
            showStatus(`Error: ${error.message}`, 'error');
            return false;
        }
    }
    
    // Function to check storage status
    function checkStorageStatus() {
        const storedCSS = localStorage.getItem('tailwind_css');
        const statusDiv = document.getElementById('storage-status');
        
        if (storedCSS) {
            statusDiv.innerHTML = '<span style="color: green;">✓ CSS available in storage</span>';
        } else {
            statusDiv.innerHTML = '<span style="color: orange;">⚠ No CSS in storage. Visit frontend while logged in first.</span>';
        }
    }
    
    // Check storage status on page load
    document.addEventListener('DOMContentLoaded', checkStorageStatus);
    
    // Clear storage button
    document.getElementById('clear-storage-btn').addEventListener('click', () => {
        localStorage.removeItem('tailwind_css');
        checkStorageStatus();
        showStatus('Storage cleared', 'info');
    });
    
    // Capture from frontend
    document.getElementById('capture-frontend-btn').addEventListener('click', async () => {
        hideStatus();
        showStatus('Capturing CSS from frontend...', 'info');
        
        try {
            // Check if we have CSS stored in localStorage
            const storedCSS = localStorage.getItem('tailwind_css');
            
            if (storedCSS) {
                await saveCSS(storedCSS, 'theme-options');
                showStatus('CSS captured from localStorage successfully!', 'success');
            } else {
                showStatus('No CSS found in localStorage. Please visit the frontend while logged in first.', 'warning');
                
                // Open frontend in new tab for user to visit
                const frontendWindow = window.open('<?php echo home_url(); ?>', '_blank');
                
                showStatus('Frontend opened in new tab. Please visit it while logged in, then return here and click "Capture CSS" again.', 'info');
            }
        } catch (error) {
            showStatus(`Error: ${error.message}`, 'error');
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

