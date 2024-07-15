jQuery(document).ready(function ($) {
    $('#poirot_extract_content').on('click', function () {
        var url = $('#poirot_website_url').val();
        if (!url) {
            alert('Please enter a URL.');
            return;
        }

        $.ajax({
            url: poirot_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'poirot_extract_content',
                url: url,
                nonce: poirot_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#poirot_extracted_content').html(response.data);
                    // Call the JavaScript function to add content to Gutenberg editor
                    window.addContentToGutenberg(response.data);
                } else {
                    alert(response.data);
                }
            }
        });
    });
});
