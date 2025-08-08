<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACFberg
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header(); 
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-lg overflow-hidden'); ?>>
                
                <!-- Page Header -->
                <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-12">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                        
                        <?php if ( get_acf_field('page_subtitle') ) : ?>
                            <p class="text-xl text-blue-100 mb-4"><?php echo esc_html(get_acf_field('page_subtitle')); ?></p>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-center space-x-4 text-sm text-blue-200">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo get_the_date(); ?>
                            </span>
                            
                            <?php if ( get_acf_field('page_author') ) : ?>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo esc_html(get_acf_field('page_author')); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>
                
                <!-- Page Content -->
                <div class="px-8 py-12">
                    
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="mb-8">
                            <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                                <?php the_post_thumbnail('large', array('class' => 'w-full h-64 object-cover')); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- ACF Fields -->
                    <?php if ( acf_is_available() ) : ?>
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-2xl font-semibold text-gray-900 mb-6">Additional Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php if ( get_acf_field('page_featured_content') ) : ?>
                                    <div class="bg-blue-50 p-6 rounded-lg">
                                        <h4 class="font-semibold text-blue-900 mb-3">Featured Content</h4>
                                        <div class="text-blue-800">
                                            <?php echo wp_kses_post(get_acf_field('page_featured_content')); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( get_acf_field('page_sidebar_content') ) : ?>
                                    <div class="bg-gray-50 p-6 rounded-lg">
                                        <h4 class="font-semibold text-gray-900 mb-3">Sidebar Content</h4>
                                        <div class="text-gray-700">
                                            <?php echo wp_kses_post(get_acf_field('page_sidebar_content')); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Page Navigation -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <?php
                                $prev_post = get_previous_post();
                                if ( $prev_post ) :
                                ?>
                                    <a href="<?php echo get_permalink($prev_post); ?>" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Previous
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <?php
                                $next_post = get_next_post();
                                if ( $next_post ) :
                                ?>
                                    <a href="<?php echo get_permalink($next_post); ?>" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                        Next
                                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </article>
            
        <?php endwhile; ?>
        
    </div>
</div>

<?php
get_footer();
