<?php
namespace Poirot;
/**
 * Handles the creation and rendering of the Poirot meta box in the post editor
 */
class MetaBox {
    /**
     * Registers the meta box with WordPress
     * 
     * This method adds a meta box to the post editor screen that allows
     * users to extract content from external websites.
     * 
     * @return void
     */
    public static function register() {
        add_meta_box(
            'poirot_meta_box',           // Unique ID
            'Poirot Content Extractor',   // Box title
            [self::class, 'render'],      // Callback function
            'post',                       // Post type
            'normal',                     // Context
            'high'                        // Priority
        );
    }

    /**
     * Renders the meta box content
     * 
     * This method:
     * 1. Retrieves any existing website URL from post meta
     * 2. Sets up the nonce field for security
     * 3. Includes the meta box view template
     * 
     * @param WP_Post $post The post object
     * @return void
     */
    public static function render($post) {
        $value = get_post_meta($post->ID, '_poirot_website_url', true);
        wp_nonce_field('poirot_save_meta_box_data', 'poirot_meta_box_nonce');

        include POIROT_PLUGIN_PATH . 'views/meta-box.php';
    }
}