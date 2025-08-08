<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
    
    <?php if (is_user_logged_in()): ?>
    <!-- Add wpApiSettings for REST API calls -->
    <script>
    window.wpApiSettings = {
        nonce: '<?php echo wp_create_nonce('wp_rest'); ?>'
    };
    </script>
    
    <!-- Load Tailwind CDN for logged-in users -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Capture CSS after Tailwind loads and save to server
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const tailwindStyle = [...document.querySelectorAll('style')]
                .find(s => s.innerText.includes('--tw'));
            
            if (tailwindStyle) {
                const css = tailwindStyle.innerText;
                
                // Store CSS in localStorage for admin capture
                localStorage.setItem('tailwind_css', css);
                
                // Automatically save CSS to server for this page type
                savePageCSS(css);
                
                console.log('Tailwind CSS captured and saved for this page');
            }
        }, 1000);
    });
    
    // Function to save CSS for current page type
    async function savePageCSS(css) {
        try {
            const res = await fetch('/wp-json/tailwind-cache/v1/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                },
                body: JSON.stringify({
                    type: getPageType(),
                    post_id: <?php echo is_singular() ? get_the_ID() : 0; ?>,
                    post_type: '<?php echo get_post_type(); ?>',
                    css: css
                })
            });
            
            if (res.ok) {
                const result = await res.json();
                console.log('CSS saved for page type:', getPageType(), 'as', result.saved);
            } else {
                const error = await res.json();
                console.log('Error saving CSS:', error);
            }
        } catch (error) {
            console.log('Error saving CSS:', error);
        }
    }
    
    // Function to determine page type
    function getPageType() {
        <?php
        if (is_home() || is_front_page()) {
            echo "return 'home';";
        } elseif (is_singular('post')) {
            echo "return 'single';";
        } elseif (is_singular('page')) {
            echo "return 'page';";
        } elseif (is_singular()) {
            echo "return 'single';";  // All custom post types use single.css
        } elseif (is_archive()) {
            echo "return 'archive';";
        } elseif (is_search()) {
            echo "return 'search';";
        } elseif (is_404()) {
            echo "return '404';";
        } else {
            echo "return 'default';";
        }
        ?>
    }
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