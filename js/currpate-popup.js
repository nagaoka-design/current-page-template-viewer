/**
 * Current Page Template Viewer - Popup Script
 * Version: 1.0.1
 */

(function () {
    'use strict';

    // Get DOM elements
    var display = document.getElementById('currpate-current-page-template-viewer-display');
    var popup = document.getElementById('currpate-current-page-template-viewer-popup');
    var close = document.getElementById('currpate-current-page-template-viewer-close');

    // Set event listeners only if elements exist
    if (display && popup && close) {

        // Open popup when display area is clicked
        display.addEventListener('click', function (e) {
            e.preventDefault();
            popup.style.display = 'block';
        });

        // Close popup when close button is clicked
        close.addEventListener('click', function (e) {
            e.preventDefault();
            popup.style.display = 'none';
        });

        // Close popup when background is clicked
        popup.addEventListener('click', function (e) {
            if (e.target === popup) {
                popup.style.display = 'none';
            }
        });

        // Close popup with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && popup.style.display === 'block') {
                popup.style.display = 'none';
            }
        });
    }

})();