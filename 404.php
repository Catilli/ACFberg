<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ACFberg
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="min-h-screen bg-gradient-to-br from-slate-900 to-blue-950 flex items-center justify-center p-8">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-lg shadow-lg p-8" style="box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.3), 0 10px 10px -5px rgba(245, 158, 11, 0.2);">
            <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4"><?php esc_html_e('Page Not Found', get_text_domain()); ?></h2>
            <p class="text-gray-600 mb-8"><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', get_text_domain()); ?></p>
            
            <div class="space-y-4">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                    <?php esc_html_e('Go to Homepage', get_text_domain()); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
