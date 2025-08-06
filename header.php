<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', get_text_domain()); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="flex items-center justify-between py-4">
                
                <!-- Site Branding -->
                <div class="site-branding flex items-center">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/media/default-logo.svg" alt="<?php bloginfo('name'); ?>" class="h-8 w-auto">
                            </a>
                        </h1>
                        <?php
                    }
                    ?>
                    
                    <?php
                    $acfberg_description = get_bloginfo('description', 'display');
                    if ($acfberg_description || is_customize_preview()) :
                        ?>
                        <p class="site-description text-gray-600 ml-4">
                            <?php echo $acfberg_description; ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Navigation -->
                <nav id="site-navigation" class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'flex space-x-4',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </header>