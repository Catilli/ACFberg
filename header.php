<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
    
    <?php if (is_user_logged_in()): ?>
    <!-- Load Tailwind CDN for logged-in users -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Capture CSS after Tailwind loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const tailwindStyle = [...document.querySelectorAll('style')]
                .find(s => s.innerText.includes('--tw'));
            
            if (tailwindStyle) {
                // Store CSS in localStorage for admin capture
                localStorage.setItem('tailwind_css', tailwindStyle.innerText);
                console.log('Tailwind CSS captured and stored');
            }
        }, 1000);
    });
    </script>
    <?php endif; ?>
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
                    $logo = get_acf_option('site_logo', get_template_directory_uri() . '/assets/media/default-logo.svg');
                    if (!empty($logo)) {
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>" class="h-8 w-auto">
                            </a>
                        <?php
                    }
                    ?>
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