/**
 * Responsive Google Forms reCAPTCHA Handler
 * 
 * This script handles responsive behavior for Google Forms reCAPTCHA
 * elements to ensure they work properly on all screen sizes.
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        initResponsiveRecaptcha();
    });
    
    // Also initialize on window resize
    $(window).on('resize', function() {
        initResponsiveRecaptcha();
    });
    
    function initResponsiveRecaptcha() {
        // Find all reCAPTCHA iframes
        $('iframe[src*="recaptcha"]').each(function() {
            var $iframe = $(this);
            var $container = $iframe.parent();
            
            // Make container responsive
            $container.css({
                'max-width': '100%',
                'overflow': 'hidden'
            });
            
            // Make iframe responsive
            $iframe.css({
                'width': '100%',
                'max-width': '302px', // Standard reCAPTCHA width
                'height': 'auto',
                'min-height': '76px' // Minimum height for reCAPTCHA
            });
        });
        
        // Handle Google Forms reCAPTCHA specifically
        $('iframe[src*="google.com/recaptcha"]').each(function() {
            var $iframe = $(this);
            
            // Add responsive wrapper if not already present
            if (!$iframe.parent().hasClass('g-recaptcha-responsive')) {
                $iframe.wrap('<div class="g-recaptcha-responsive"></div>');
            }
            
            // Style the wrapper
            $iframe.parent().css({
                'position': 'relative',
                'width': '100%',
                'max-width': '302px',
                'margin': '0 auto'
            });
        });
    }
    
    // Handle dynamic content loading (for AJAX forms)
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('iframe[src*="recaptcha"]').length) {
            setTimeout(function() {
                initResponsiveRecaptcha();
            }, 100);
        }
    });
    
})(jQuery);
