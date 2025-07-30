    <footer id="colophon" class="site-footer bg-gray-900 text-white">
        <div class="container mx-auto px-4 py-8">
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