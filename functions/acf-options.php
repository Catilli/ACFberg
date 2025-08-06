<?php
/**
 * ACF Options Pages
 */

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'theme-options',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
    
    acf_add_options_sub_page(array(
        'page_title' => 'Tailwind CSS Manager',
        'menu_title' => 'Tailwind CSS',
        'parent_slug' => 'theme-options',
        'capability' => 'edit_posts'
    ));
}

/**
 * Add custom fields for Tailwind CSS management
 */
add_action('acf/init', function() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_tailwind_css_manager',
            'title' => 'Tailwind CSS Manager',
            'fields' => array(
                array(
                    'key' => 'field_tailwind_css_status',
                    'label' => 'CSS Status',
                    'name' => 'tailwind_css_status',
                    'type' => 'message',
                    'message' => 'This field will be populated dynamically',
                    'wrapper' => array(
                        'width' => '100',
                        'class' => '',
                        'id' => ''
                    )
                ),
                array(
                    'key' => 'field_manual_capture',
                    'label' => 'Manual Capture',
                    'name' => 'manual_capture',
                    'type' => 'button_group',
                    'choices' => array(
                        'capture' => 'Capture Current CSS',
                        'clear' => 'Clear Stored CSS'
                    ),
                    'default_value' => '',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal'
                ),
                array(
                    'key' => 'field_stored_css_preview',
                    'label' => 'Stored CSS Preview',
                    'name' => 'stored_css_preview',
                    'type' => 'textarea',
                    'readonly' => 1,
                    'rows' => 20,
                    'wrapper' => array(
                        'width' => '100',
                        'class' => '',
                        'id' => ''
                    )
                )
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-options_page_tailwind-css'
                    )
                )
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => ''
        ));
    }
});

/**
 * Handle manual capture and clear actions
 */
add_action('acf/save_post', function($post_id) {
    if ($post_id === 'options') {
        $manual_capture = get_field('manual_capture', 'option');
        
        if ($manual_capture === 'capture') {
            // Trigger CSS capture
            do_action('tailwind_manual_capture');
            
            // Clear the field
            update_field('manual_capture', '', 'option');
        } elseif ($manual_capture === 'clear') {
            // Clear stored CSS
            delete_option('tailwind_captured_css');
            delete_option('tailwind_css_last_updated');
            delete_option('tailwind_visited_pages');
            
            // Clear the field
            update_field('manual_capture', '', 'option');
        }
    }
}, 20);

/**
 * Populate dynamic fields
 */
add_filter('acf/load_value/name=tailwind_css_status', function($value) {
    $last_updated = get_option('tailwind_css_last_updated', 0);
    $stored_css = get_stored_tailwind_css();
    
    if ($last_updated > 0 && !empty($stored_css)) {
        $date = date('Y-m-d H:i:s', $last_updated);
        $size = strlen($stored_css);
        return "✅ CSS captured on {$date} ({$size} characters)";
    } else {
        return "⚠️ No CSS captured yet";
    }
});

add_filter('acf/load_value/name=stored_css_preview', function($value) {
    return get_stored_tailwind_css();
});

/**
 * Add visited pages info to the status field
 */
add_filter('acf/load_value/name=tailwind_css_status', function($value) {
    $last_updated = get_option('tailwind_css_last_updated', 0);
    $stored_css = get_stored_tailwind_css();
    $visited_pages = get_option('tailwind_visited_pages', array());
    
    if ($last_updated > 0 && !empty($stored_css)) {
        $date = date('Y-m-d H:i:s', $last_updated);
        $size = strlen($stored_css);
        $page_count = count($visited_pages);
        return "✅ CSS captured on {$date} ({$size} characters, {$page_count} pages visited)";
    } else {
        return "⚠️ No CSS captured yet";
    }
});

/**
 * Manual capture action handler
 */
add_action('tailwind_manual_capture', function() {
    // This will be triggered when the capture button is clicked
    // The actual capture happens on the next page load
    update_option('tailwind_force_capture', true);
}); 