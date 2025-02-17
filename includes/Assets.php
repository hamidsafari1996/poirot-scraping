<?php
namespace Poirot;

/**
 * Class responsible for managing WordPress asset (scripts and styles) enqueuing
 */
class Assets {
    /**
     * Enqueues required JavaScript files for the plugin
     * 
     * @param string $hook The current admin page hook
     */
    public static function enqueue($hook) {
        // Only load scripts on post edit or new post pages
        if ($hook == 'post.php' || $hook == 'post-new.php') {
            // Enqueue main plugin script with jQuery dependency
            wp_enqueue_script('poirot_script', 
                plugin_dir_url(__FILE__) . '../assets/js/poirot.js', 
                ['jquery'], 
                null, 
                true
            );

            // Enqueue Gutenberg-specific script with block editor dependencies
            wp_enqueue_script('poirot_gutenberg_script', 
                plugin_dir_url(__FILE__) . '../assets/js/poirot-gutenberg.js', 
                ['wp-blocks', 'wp-editor', 'wp-element', 'wp-components', 'wp-data'], 
                null, 
                true
            );

            // Localize script to make AJAX URL and nonce available in JavaScript
            wp_localize_script('poirot_script', 'poirot_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('poirot_nonce')
            ]);
        }
    }
}