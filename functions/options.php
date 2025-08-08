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
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">CSS Regeneration Options</h3>
            <p class="text-blue-700 mb-4">Choose how you want to regenerate the CSS:</p>
            
            <div class="space-y-4">
                <!-- Option 1: Capture from frontend -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">Option 1: Capture from Frontend</h4>
                    <p class="text-sm text-gray-600 mb-3">Visit your frontend while logged in, then capture the Tailwind CSS.</p>
                    <div class="space-y-2">
                        <a href="<?php echo home_url(); ?>" target="_blank" class="button button-primary">Visit Frontend</a>
                        <button id="capture-frontend-btn" class="button button-secondary">Capture CSS</button>
                        <button id="clear-storage-btn" class="button">Clear Storage</button>
                    </div>
                    <div id="storage-status" class="mt-2 text-sm text-gray-600"></div>
                </div>
                
                <!-- Option 2: Manual CSS input -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">Option 2: Manual CSS input</h4>
                    <p class="text-sm text-gray-600 mb-3">Paste your Tailwind CSS here:</p>
                    <textarea id="manual-css-input" rows="8" class="widefat" placeholder="Paste your Tailwind CSS here..."></textarea>
                    <button id="regenerate-manual-btn" class="button button-secondary mt-2">Save CSS</button>
                </div>
                
                <!-- Option 3: Use default styles -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">Option 3: Use default styles</h4>
                    <p class="text-sm text-gray-600 mb-3">Use the default Tailwind styles from style.json.</p>
                    <button id="regenerate-default-btn" class="button">Use Default Styles</button>
                </div>
            </div>
        </div>
        
        <div id="regeneration-status" class="hidden">
            <div class="notice notice-info">
                <p><strong>Status:</strong> <span id="status-text">Processing...</span></p>
            </div>
        </div>
    </div>

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
    
    // Option 1: Capture from frontend
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
    
    // Option 2: Manual CSS input
    document.getElementById('regenerate-manual-btn').addEventListener('click', async () => {
        hideStatus();
        const cssInput = document.getElementById('manual-css-input');
        const css = cssInput.value.trim();
        
        if (!css) {
            showStatus('Please enter some CSS first.', 'warning');
            return;
        }
        
        showStatus('Saving manual CSS...', 'info');
        await saveCSS(css, 'theme-options');
    });
    
    // Option 3: Use default styles
    document.getElementById('regenerate-default-btn').addEventListener('click', async () => {
        hideStatus();
        showStatus('Loading default styles...', 'info');
        
        try {
            const res = await fetch('<?php echo get_template_directory_uri(); ?>/assets/style.json');
            if (res.ok) {
                const styleData = await res.json();
                const css = Object.values(styleData.css).join('\n');
                await saveCSS(css, 'theme-options');
            } else {
                showStatus('Failed to load default styles from style.json', 'error');
            }
        } catch (error) {
            showStatus(`Error loading default styles: ${error.message}`, 'error');
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

