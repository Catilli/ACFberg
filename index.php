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
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-6">
                <span class="sr-only">ACFberg</span>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/media/default-logo.svg" alt="ACFberg Theme" class="h-16 md:h-20 mx-auto">
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Advanced WordPress theme with automatic Tailwind CSS capture and intelligent caching system
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 p-8 md:p-12 mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Tailwind CSS Capture System
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Experience the power of automatic CSS capture and intelligent caching for optimal performance.
                </p>
            </div>
            
            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <!-- Card 1 - Performance -->
                <div class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Lightning Fast</h3>
                    <p class="text-blue-100 leading-relaxed">
                        Static CSS files for logged-out users with zero CDN dependencies and instant loading.
                    </p>
                </div>
                
                <!-- Card 2 - Intelligence -->
                <div class="group bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Smart Capture</h3>
                    <p class="text-green-100 leading-relaxed">
                        Automatic CSS capture for each page type with intelligent file management.
                    </p>
                </div>
                
                <!-- Card 3 - Flexibility -->
                <div class="group bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Page Specific</h3>
                    <p class="text-purple-100 leading-relaxed">
                        Individual CSS files for each page type with optimized loading and caching.
                    </p>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-8 border border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">How It Works</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                        <h4 class="font-semibold text-gray-900 mb-2">Logged-in Visit</h4>
                        <p class="text-gray-600 text-sm">Admin visits page, Tailwind CDN loads and CSS is automatically captured</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                        <h4 class="font-semibold text-gray-900 mb-2">Smart Storage</h4>
                        <p class="text-gray-600 text-sm">CSS is saved to page-specific files in the cache directory</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                        <h4 class="font-semibold text-gray-900 mb-2">Fast Delivery</h4>
                        <p class="text-gray-600 text-sm">Logged-out users receive optimized static CSS without CDN</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-blue-600 mb-2">100%</div>
                <div class="text-gray-600">Performance</div>
            </div>
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-green-600 mb-2">Auto</div>
                <div class="text-gray-600">Capture</div>
            </div>
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-purple-600 mb-2">Zero</div>
                <div class="text-gray-600">Dependencies</div>
            </div>
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-orange-600 mb-2">Smart</div>
                <div class="text-gray-600">Caching</div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl p-8 shadow-2xl">
                <h3 class="text-2xl font-bold mb-4">Ready to Experience the Future?</h3>
                <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                    Join thousands of developers who trust ACFberg for their WordPress projects.
                </p>
                <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    Get Started Today
                </button>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>