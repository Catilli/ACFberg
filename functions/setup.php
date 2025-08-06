<?php
/**
 * Theme Setup
 */

/**
 * Register navigation menus
 */
function my_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', get_text_domain()),
    ));
}
add_action('init', 'my_register_menus');

/**
 * Add theme support
 */
function my_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add support for Gutenberg wide and full-width blocks
    add_theme_support('align-wide');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary', get_text_domain()),
            'slug'  => 'primary',
            'color' => '#3b82f6',
        ),
        array(
            'name'  => __('Secondary', get_text_domain()),
            'slug'  => 'secondary',
            'color' => '#64748b',
        ),
        array(
            'name'  => __('Dark', get_text_domain()),
            'slug'  => 'dark',
            'color' => '#1e293b',
        ),
        array(
            'name'  => __('Light', get_text_domain()),
            'slug'  => 'light',
            'color' => '#f8fafc',
        ),
    ));
}
add_action('after_setup_theme', 'my_theme_setup'); 