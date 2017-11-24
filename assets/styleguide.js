(function() {

    /**
     * Set the height of the iframe to the height of its content.
     *
     * @param {HTMLElement} iframe
     */
    function setIframeHeight(iframe) {
        iframe.addEventListener('load', () => {
            iframe.height = 1;
            iframe.height = iframe.contentWindow.document.body.scrollHeight;

            // In some cases the iframe is still slightly smaller than the content.
            setTimeout(() => {
                iframe.height = iframe.contentWindow.document.body.scrollHeight;
            }, 25);
        });
    }

    const Styleguide = {
        init() {
            this.processIframes();
        },

        /**
         * Go thgrough all the preview iframes and set their heights
         * to the heights of the content.
         */
        processIframes() {
            const iframes = Array.from(document.querySelectorAll('iframe'));
            if (!iframes.length) {
                return;
            }

            for (let i = 0; i < iframes.length; i++) {
                setIframeHeight(iframes[i]);
            }
        }
    };

    // Initialize styleguide functionality.
    Styleguide.init();

})();
