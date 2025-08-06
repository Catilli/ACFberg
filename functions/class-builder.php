<?php
/**
 * Webflow-like Class System for WordPress
 * Visual class management with semantic naming
 */

/**
 * Register Class Builder Scripts
 */
function class_builder_enqueue_scripts() {
    if (is_admin()) {
        wp_enqueue_script(
            'class-builder',
            get_template_directory_uri() . '/assets/js/class-builder.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'class-builder-style',
            get_template_directory_uri() . '/assets/css/class-builder.css',
            array(),
            '1.0.0'
        );
        
        // Localize script with class definitions
        wp_localize_script('class-builder', 'classBuilderData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('class_builder_nonce'),
            'classes' => get_semantic_classes()
        ));
    }
}
add_action('admin_enqueue_scripts', 'class_builder_enqueue_scripts');

/**
 * Define Semantic Classes (Webflow-style)
 */
function get_semantic_classes() {
    return array(
        'layout' => array(
            'container' => array(
                'css' => 'max-width: 1200px; margin: 0 auto; padding: 0 20px;',
                'description' => 'Centered container with max width'
            ),
            'flex' => array(
                'css' => 'display: flex;',
                'description' => 'Flexbox container'
            ),
            'grid' => array(
                'css' => 'display: grid;',
                'description' => 'CSS Grid container'
            ),
            'stack' => array(
                'css' => 'display: flex; flex-direction: column; gap: 1rem;',
                'description' => 'Vertical stack with spacing'
            ),
            'cluster' => array(
                'css' => 'display: flex; flex-wrap: wrap; gap: 1rem;',
                'description' => 'Horizontal cluster with wrapping'
            )
        ),
        
        'spacing' => array(
            'space-xs' => array(
                'css' => 'padding: 0.25rem;',
                'description' => 'Extra small spacing'
            ),
            'space-sm' => array(
                'css' => 'padding: 0.5rem;',
                'description' => 'Small spacing'
            ),
            'space-md' => array(
                'css' => 'padding: 1rem;',
                'description' => 'Medium spacing'
            ),
            'space-lg' => array(
                'css' => 'padding: 1.5rem;',
                'description' => 'Large spacing'
            ),
            'space-xl' => array(
                'css' => 'padding: 2rem;',
                'description' => 'Extra large spacing'
            )
        ),
        
        'typography' => array(
            'text-xs' => array(
                'css' => 'font-size: 0.75rem; line-height: 1rem;',
                'description' => 'Extra small text'
            ),
            'text-sm' => array(
                'css' => 'font-size: 0.875rem; line-height: 1.25rem;',
                'description' => 'Small text'
            ),
            'text-base' => array(
                'css' => 'font-size: 1rem; line-height: 1.5rem;',
                'description' => 'Base text size'
            ),
            'text-lg' => array(
                'css' => 'font-size: 1.125rem; line-height: 1.75rem;',
                'description' => 'Large text'
            ),
            'text-xl' => array(
                'css' => 'font-size: 1.25rem; line-height: 1.75rem;',
                'description' => 'Extra large text'
            ),
            'text-2xl' => array(
                'css' => 'font-size: 1.5rem; line-height: 2rem;',
                'description' => '2XL text'
            ),
            'text-3xl' => array(
                'css' => 'font-size: 1.875rem; line-height: 2.25rem;',
                'description' => '3XL text'
            ),
            'text-4xl' => array(
                'css' => 'font-size: 2.25rem; line-height: 2.5rem;',
                'description' => '4XL text'
            )
        ),
        
        'colors' => array(
            'text-primary' => array(
                'css' => 'color: #3b82f6;',
                'description' => 'Primary text color'
            ),
            'text-secondary' => array(
                'css' => 'color: #64748b;',
                'description' => 'Secondary text color'
            ),
            'text-dark' => array(
                'css' => 'color: #1e293b;',
                'description' => 'Dark text color'
            ),
            'text-light' => array(
                'css' => 'color: #f8fafc;',
                'description' => 'Light text color'
            ),
            'bg-primary' => array(
                'css' => 'background-color: #3b82f6;',
                'description' => 'Primary background'
            ),
            'bg-secondary' => array(
                'css' => 'background-color: #64748b;',
                'description' => 'Secondary background'
            ),
            'bg-dark' => array(
                'css' => 'background-color: #1e293b;',
                'description' => 'Dark background'
            ),
            'bg-light' => array(
                'css' => 'background-color: #f8fafc;',
                'description' => 'Light background'
            )
        ),
        
        'components' => array(
            'button' => array(
                'css' => 'display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.2s;',
                'description' => 'Base button styles'
            ),
            'button-primary' => array(
                'css' => 'background-color: #3b82f6; color: white; border: 1px solid #3b82f6;',
                'description' => 'Primary button variant'
            ),
            'button-secondary' => array(
                'css' => 'background-color: transparent; color: #3b82f6; border: 1px solid #3b82f6;',
                'description' => 'Secondary button variant'
            ),
            'button-outline' => array(
                'css' => 'background-color: transparent; color: #64748b; border: 1px solid #e2e8f0;',
                'description' => 'Outline button variant'
            ),
            'card' => array(
                'css' => 'background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); padding: 1.5rem;',
                'description' => 'Card component'
            ),
            'input' => array(
                'css' => 'width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; line-height: 1.5rem;',
                'description' => 'Input field styles'
            )
        ),
        
        'states' => array(
            'hover' => array(
                'css' => ':hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }',
                'description' => 'Hover state effects'
            ),
            'focus' => array(
                'css' => ':focus { outline: 2px solid #3b82f6; outline-offset: 2px; }',
                'description' => 'Focus state styles'
            ),
            'active' => array(
                'css' => ':active { transform: translateY(0); }',
                'description' => 'Active state effects'
            )
        ),
        
        'responsive' => array(
            'mobile-first' => array(
                'css' => '@media (min-width: 640px) { /* sm styles */ } @media (min-width: 768px) { /* md styles */ } @media (min-width: 1024px) { /* lg styles */ } @media (min-width: 1280px) { /* xl styles */ }',
                'description' => 'Mobile-first responsive approach'
            ),
            'hide-mobile' => array(
                'css' => 'display: none; @media (min-width: 768px) { display: block; }',
                'description' => 'Hidden on mobile, visible on desktop'
            ),
            'show-mobile' => array(
                'css' => 'display: block; @media (min-width: 768px) { display: none; }',
                'description' => 'Visible on mobile, hidden on desktop'
            )
        )
    );
}

/**
 * AJAX handler for class preview
 */
function class_builder_preview() {
    check_ajax_referer('class_builder_nonce', 'nonce');
    
    $classes = sanitize_text_field($_POST['classes']);
    $html = sanitize_textarea_field($_POST['html']);
    
    // Generate CSS for the classes
    $css = generate_css_for_classes($classes);
    
    wp_send_json_success(array(
        'css' => $css,
        'preview_html' => apply_classes_to_html($html, $classes)
    ));
}
add_action('wp_ajax_class_builder_preview', 'class_builder_preview');

/**
 * Generate CSS for selected classes
 */
function generate_css_for_classes($classes) {
    $all_classes = get_semantic_classes();
    $css = '';
    
    $class_array = explode(' ', $classes);
    
    foreach ($class_array as $class) {
        $class = trim($class);
        if (empty($class)) continue;
        
        // Find the class in our definitions
        foreach ($all_classes as $category => $category_classes) {
            if (isset($category_classes[$class])) {
                $css .= ".$class {\n";
                $css .= "  " . $category_classes[$class]['css'] . "\n";
                $css .= "}\n\n";
                break;
            }
        }
    }
    
    return $css;
}

/**
 * Apply classes to HTML
 */
function apply_classes_to_html($html, $classes) {
    // Simple class application - in real implementation, you'd want more sophisticated parsing
    return str_replace('<div>', '<div class="' . esc_attr($classes) . '">', $html);
}

/**
 * Add Class Builder meta box
 */
function add_class_builder_meta_box() {
    add_meta_box(
        'class_builder',
        'Class Builder',
        'class_builder_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_class_builder_meta_box');

/**
 * Class Builder meta box callback
 */
function class_builder_meta_box_callback($post) {
    ?>
    <div id="class-builder">
        <div class="class-categories">
            <h4>Layout</h4>
            <div class="class-buttons" data-category="layout">
                <button class="class-btn" data-class="container">Container</button>
                <button class="class-btn" data-class="flex">Flex</button>
                <button class="class-btn" data-class="grid">Grid</button>
                <button class="class-btn" data-class="stack">Stack</button>
                <button class="class-btn" data-class="cluster">Cluster</button>
            </div>
            
            <h4>Spacing</h4>
            <div class="class-buttons" data-category="spacing">
                <button class="class-btn" data-class="space-xs">XS</button>
                <button class="class-btn" data-class="space-sm">SM</button>
                <button class="class-btn" data-class="space-md">MD</button>
                <button class="class-btn" data-class="space-lg">LG</button>
                <button class="class-btn" data-class="space-xl">XL</button>
            </div>
            
            <h4>Typography</h4>
            <div class="class-buttons" data-category="typography">
                <button class="class-btn" data-class="text-xs">XS</button>
                <button class="class-btn" data-class="text-sm">SM</button>
                <button class="class-btn" data-class="text-base">Base</button>
                <button class="class-btn" data-class="text-lg">LG</button>
                <button class="class-btn" data-class="text-xl">XL</button>
                <button class="class-btn" data-class="text-2xl">2XL</button>
                <button class="class-btn" data-class="text-3xl">3XL</button>
                <button class="class-btn" data-class="text-4xl">4XL</button>
            </div>
            
            <h4>Colors</h4>
            <div class="class-buttons" data-category="colors">
                <button class="class-btn" data-class="text-primary">Primary</button>
                <button class="class-btn" data-class="text-secondary">Secondary</button>
                <button class="class-btn" data-class="text-dark">Dark</button>
                <button class="class-btn" data-class="text-light">Light</button>
                <button class="class-btn" data-class="bg-primary">BG Primary</button>
                <button class="class-btn" data-class="bg-secondary">BG Secondary</button>
                <button class="class-btn" data-class="bg-dark">BG Dark</button>
                <button class="class-btn" data-class="bg-light">BG Light</button>
            </div>
            
            <h4>Components</h4>
            <div class="class-buttons" data-category="components">
                <button class="class-btn" data-class="button">Button</button>
                <button class="class-btn" data-class="button-primary">Primary</button>
                <button class="class-btn" data-class="button-secondary">Secondary</button>
                <button class="class-btn" data-class="button-outline">Outline</button>
                <button class="class-btn" data-class="card">Card</button>
                <button class="class-btn" data-class="input">Input</button>
            </div>
        </div>
        
        <div class="selected-classes">
            <h4>Selected Classes</h4>
            <div id="selected-classes-list"></div>
            <input type="text" id="classes-input" placeholder="Classes will appear here..." readonly>
        </div>
        
        <div class="preview-section">
            <h4>Preview</h4>
            <div id="class-preview"></div>
        </div>
    </div>
    <?php
} 