(function (wp) {
    window.addContentToGutenberg = function (htmlContent) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(htmlContent, 'text/html');

        // Extract page title
        var title = doc.querySelector('h4').innerText;
        wp.data.dispatch('core/editor').editPost({ title: title });

        // Extract paragraphs
        var paragraphs = doc.querySelectorAll('p');
        paragraphs.forEach(function (p) {
            wp.data.dispatch('core/block-editor').insertBlocks(
                wp.blocks.createBlock('core/paragraph', { content: p.innerText })
            );
        });
    };
})(window.wp);
