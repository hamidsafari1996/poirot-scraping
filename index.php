<?php
/*
Plugin Name: Poirot Content Extractor
Description: A WordPress plugin to extract content and images from a given URL.
Version: 1.0
Author: Hamid Safari
*/

// Add a meta box to post edit screen
function poirot_add_meta_box() {
    add_meta_box(
        'poirot_meta_box',
        'poirot Content Extractor',
        'poirot_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'poirot_add_meta_box');

// Display the meta box
function poirot_meta_box_callback($post) {
    wp_nonce_field('poirot_save_meta_box_data', 'poirot_meta_box_nonce');

    $value = get_post_meta($post->ID, '_poirot_website_url', true);

    echo '<label for="poirot_website_url">Website URL:</label> ';
    echo '<input type="text" id="poirot_website_url" name="poirot_website_url" value="' . esc_attr($value) . '" size="50" />';
    echo '<button type="button" id="poirot_extract_content" class="button button-primary">Extract Content</button>';
    echo '<div id="poirot_extracted_content" style="margin-top: 20px;"></div>';
}

// Enqueue JavaScript script for handling the button and extracting content
function poirot_enqueue_scripts($hook) {
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script('poirot_script', plugin_dir_url(__FILE__) . 'poirot.js', array('jquery'), null, true);
        wp_enqueue_script('poirot_gutenberg_script', plugin_dir_url(__FILE__) . 'poirot-gutenberg.js', array('wp-blocks', 'wp-editor', 'wp-element', 'wp-components', 'wp-data'), null, true);
        wp_localize_script('poirot_script', 'poirot_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('poirot_nonce')
        ));
    }
}
add_action('admin_enqueue_scripts', 'poirot_enqueue_scripts');

// Handle the AJAX request to extract content
function poirot_extract_content() {
    check_ajax_referer('poirot_nonce', 'nonce');

    $url = esc_url_raw($_POST['url']);
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_send_json_error('Failed to retrieve content. Please check the URL and try again.');
    }

    $html = wp_remote_retrieve_body($response);
    $doc = new DOMDocument();
    @$doc->loadHTML($html);

    $title = $doc->getElementsByTagName('title')->item(0)->nodeValue;
    $output = '<h4>Page Title: ' . $title . '</h4>';

    $output .= '<h4>Images:</h4>';
    $images = $doc->getElementsByTagName('img');
    foreach ($images as $image) {
        $output .= '<img src="' . $image->getAttribute('src') . '" alt="" width="200" style="margin: 10px;">';
    }

    $output .= '<h4>Content:</h4>';
    $paragraphs = $doc->getElementsByTagName('p');
    foreach ($paragraphs as $paragraph) {
        $output .= '<p>' . $paragraph->nodeValue . '</p>';
    }

    wp_send_json_success($output);
}
add_action('wp_ajax_poirot_extract_content', 'poirot_extract_content');