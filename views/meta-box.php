<?php
/**
 * Template for the Poirot content extractor meta box
 * 
 * This template displays a form with three main components:
 * 1. URL Input Field: Allows users to enter the website URL to extract content from
 * 2. Extract Button: Triggers the AJAX-based content extraction process
 * 3. Content Container: Displays the extracted content after successful extraction
 * 
 * @var string $value The saved website URL from post meta, passed from MetaBox class
 */

// Ensure $value is defined and sanitized for security
$value = isset($value) ? esc_url($value) : '';
?>
<div class="poirot-meta-box">
    <p>
        <!-- URL input field with screen reader accessible label -->
        <label for="poirot_website_url" class="screen-reader-text">Website URL:</label>
        <input 
            type="url" 
            id="poirot_website_url" 
            name="poirot_website_url" 
            value="<?php echo esc_attr($value); ?>" 
            size="50" 
            placeholder="https://"
            required
        />
    </p>
    <p>
        <!-- Extract button with ARIA attributes for accessibility -->
        <button 
            type="button" 
            id="poirot_extract_content" 
            class="button button-primary"
            aria-controls="poirot_extracted_content"
        >
            <?php esc_html_e('Extract Content', 'poirot'); ?>
        </button>
    </p>
    <!-- Container for dynamically loaded content with ARIA live region -->
    <div 
        id="poirot_extracted_content" 
        class="poirot-extracted-content" 
        aria-live="polite"
    ></div>
</div>