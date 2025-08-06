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

get_header(); 
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tailwind CSS Capture Test</h1>
            <p class="text-lg text-gray-600 mb-6">This page demonstrates the admin-only Tailwind CDN with CSS capture functionality.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-500 text-white p-6 rounded-lg">
                    <h3 class="text-xl font-semibold mb-2">Card 1</h3>
                    <p class="text-blue-100">This card uses Tailwind classes that will be captured.</p>
                </div>
                
                <div class="bg-green-500 text-white p-6 rounded-lg">
                    <h3 class="text-xl font-semibold mb-2">Card 2</h3>
                    <p class="text-green-100">More Tailwind classes for testing the capture system.</p>
                </div>
                
                <div class="bg-purple-500 text-white p-6 rounded-lg">
                    <h3 class="text-xl font-semibold mb-2">Card 3</h3>
                    <p class="text-purple-100">Responsive design with Tailwind utilities.</p>
                </div>
            </div>
            
            <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">How it works:</h2>
                <ul class="list-disc list-inside space-y-2 text-gray-700">
                    <li><strong>Logged in users:</strong> See the Tailwind CDN in action with real-time CSS generation</li>
                    <li><strong>Non-logged in users:</strong> See the captured CSS served from WordPress options</li>
                    <li><strong>Admin users:</strong> Can manually capture CSS via Theme Options → Tailwind CSS</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();