// Wait for the DOM to be fully loaded before executing
jQuery(document).ready(function ($) {
    // Add click event listener to the extract content button
    $('#poirot_extract_content').on('click', function () {
        // Get the URL from the input field
        var url = $('#poirot_website_url').val();
        
        // Validate that a URL was entered
        if (!url) {
            alert('Please enter a URL.');
            return;
        }

        // Make AJAX request to WordPress backend
        $.ajax({
            url: poirot_ajax.ajax_url,      // WordPress AJAX endpoint (from localized script)
            method: 'POST',                  // Using POST method for data submission
            data: {
                action: 'poirot_extract_content',  // WordPress AJAX action hook
                url: url,                          // URL to extract content from
                nonce: poirot_ajax.nonce          // Security nonce for verification
            },
            success: function (response) {
                if (response.success) {
                    // If successful, update the preview area with extracted content
                    $('#poirot_extracted_content').html(response.data);
                    // Add the extracted content to Gutenberg editor
                    window.addContentToGutenberg(response.data);
                } else {
                    // If failed, show error message
                    alert(response.data);
                }
            }
        });
    });
});
