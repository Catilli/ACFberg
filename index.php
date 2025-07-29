<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="wrapper" id="index-wrapper">
    <div class="container mx-auto px-4 py-8" id="content" tabindex="-1">
        <main class="site-main" id="main">
            
            <?php if ( have_posts() ) : ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    // Start the Loop.
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card hover:shadow-lg transition-shadow duration-300'); ?>>
                            
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="card-header p-0">
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <header class="entry-header mb-4">
                                    <?php the_title( '<h2 class="entry-title text-xl font-bold text-gray-900 mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-blue-600">', '</a></h2>' ); ?>
                                    
                                    <?php if ( 'post' === get_post_type() ) : ?>
                                        <div class="entry-meta text-sm text-gray-600 mb-3">
                                            <span class="posted-on">
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <span class="byline ml-4">
                                                <?php echo get_the_author(); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </header>

                                <div class="entry-summary text-gray-700">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer mt-4">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline text-sm">
                                        <?php esc_html_e('Read More', 'acfberg'); ?>
                                    </a>
                                </footer>
                            </div>
                        </article>
                        
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-12">
                    <?php
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => __('Previous', 'acfberg'),
                        'next_text' => __('Next', 'acfberg'),
                        'class'     => 'flex justify-center space-x-2',
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                
                <div class="text-center py-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        <?php esc_html_e('Nothing Found', 'acfberg'); ?>
                    </h2>
                    <p class="text-gray-600 mb-8">
                        <?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'acfberg'); ?>
                    </p>
                    <?php get_search_form(); ?>
                </div>
                
            <?php endif; ?>
            
        </main>
    </div>
</div>

<?php
get_footer();