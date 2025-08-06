<?php

// Global text domain - automatically uses theme folder name
$GLOBALS['textdomain'] = get_template();

// Helper function to get text domain (can be used in template files)
function get_text_domain() {
    return $GLOBALS['textdomain'];
}

// Include functions files
require_once get_template_directory() . '/functions/disable-comments.php';
require_once get_template_directory() . '/functions/class-system.php';
require_once get_template_directory() . '/functions/script-system.php';

/**
 * Enqueue scripts and styles
 */
function acfberg_enqueue_scripts() {
    // Enqueue theme's main style.css for WordPress theme info
    wp_enqueue_style(
        'style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
}
add_action('wp_enqueue_scripts', 'acfberg_enqueue_scripts');

/**
 * Add theme support
 */
function acfberg_theme_setup() {
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
add_action('after_setup_theme', 'acfberg_theme_setup');

/**
 * Register navigation menus
 */
function acfberg_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', get_text_domain()),
        'footer'  => __('Footer Menu', get_text_domain()),
    ));
}
add_action('init', 'acfberg_register_menus');
