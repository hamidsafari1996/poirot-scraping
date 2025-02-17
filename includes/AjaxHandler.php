<?php
namespace Poirot;

/**
 * Class responsible for handling AJAX requests to extract content from external URLs
 */
class AjaxHandler {
    
    /**
     * Extracts content from a provided URL via AJAX request
     * 
     * This method:
     * 1. Verifies the AJAX nonce for security
     * 2. Fetches content from the provided URL
     * 3. Parses the HTML to extract title, images, and paragraphs
     * 4. Returns formatted HTML output
     */
    public static function extract() {
        // Verify the AJAX nonce for security
        check_ajax_referer('poirot_nonce', 'nonce');

        // Sanitize and get the URL from POST data
        $url = esc_url_raw($_POST['url']);
        $response = wp_remote_get($url);

        // Handle any errors in fetching the URL
        if (is_wp_error($response)) {
            wp_send_json_error('Failed to retrieve content. Please check the URL and try again.');
        }

        // Get the HTML content and create a DOM parser
        $html = wp_remote_retrieve_body($response);
        $doc = new \DOMDocument();
        @$doc->loadHTML($html); // @ suppresses warnings from malformed HTML

        // Extract and format the page title
        $title = $doc->getElementsByTagName('title')->item(0)->nodeValue;
        $output = '<h4> ' . $title . '</h4>';

        // Extract and format all images
        $output .= '<h4>Images:</h4>';
        $images = $doc->getElementsByTagName('img');
        foreach ($images as $image) {
            $output .= '<img src="' . $image->getAttribute('src') . '" alt="" width="200" style="margin: 10px;">';
        }

        // Extract and format all paragraphs
        $output .= '<h4>Content:</h4>';
        $paragraphs = $doc->getElementsByTagName('p');
        foreach ($paragraphs as $paragraph) {
            $output .= '<p>' . $paragraph->nodeValue . '</p>';
        }

        // Send the formatted content back as JSON response
        wp_send_json_success($output);
    }
}