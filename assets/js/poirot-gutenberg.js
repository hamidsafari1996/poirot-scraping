// Immediately Invoked Function Expression (IIFE) with WordPress (wp) as parameter
(function (wp) {
    // Define a global function to add content to Gutenberg editor
    window.addContentToGutenberg = function (htmlContent) {
        // Create a DOM parser to handle the HTML string
        var parser = new DOMParser();
        // Parse the HTML content into a DOM document
        var doc = parser.parseFromString(htmlContent, 'text/html');

        // Extract page title from h4 element and update the post title
        var title = doc.querySelector('h4').innerText;
        wp.data.dispatch('core/editor').editPost({ title: title });

        // Find all paragraphs in the parsed HTML
        var paragraphs = doc.querySelectorAll('p');
        // For each paragraph, create a new Gutenberg paragraph block
        paragraphs.forEach(function (p) {
            wp.data.dispatch('core/block-editor').insertBlocks(
                // Create a new paragraph block with the text content
                wp.blocks.createBlock('core/paragraph', { content: p.innerText })
            );
        });
    };
})(window.wp);
