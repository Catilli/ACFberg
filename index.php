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
    <div class="container" id="content" tabindex="-1">
        <main class="site-main" id="main">

            <?php
            if ( have_posts() ) {
                // Start the Loop.
                while ( have_posts() ) {
                    the_post();

                    /*
                        * Include the Post-Format-specific template for the content.
                        */
                    get_template_part( 'loop-templates/content', get_post_format() );
                }
            } else {
                get_template_part( 'loop-templates/content', 'none' );
            }
            ?>

        </main>

        <?php pagination(); ?>
    </div>
</div>

<?php
get_footer();