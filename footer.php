    <footer id="colophon" class="site-footer bg-gray-900 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Footer Widget Area 1 -->
                <div class="footer-widget">
                    <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('About', 'acfberg'); ?></h3>
                    <p class="text-gray-300">
                        <?php bloginfo('description'); ?>
                    </p>
                </div>

                <!-- Footer Widget Area 2 -->
                <div class="footer-widget">
                    <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Quick Links', 'acfberg'); ?></h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'space-y-2',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>

                <!-- Footer Widget Area 3 -->
                <div class="footer-widget">
                    <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Contact', 'acfberg'); ?></h3>
                    <p class="text-gray-300">
                        <?php esc_html_e('Get in touch with us.', 'acfberg'); ?>
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php esc_html_e('All rights reserved.', 'acfberg'); ?>
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    <?php esc_html_e('Powered by', 'acfberg'); ?> 
                    <a href="https://wordpress.org/" class="text-blue-400 hover:text-blue-300" target="_blank" rel="noopener noreferrer">
                        WordPress
                    </a>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>