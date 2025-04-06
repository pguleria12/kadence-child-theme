/**
 * GrafixPoint Kadence Child Theme JavaScript
 * 
 * Handles dark mode toggle and other interactive elements
 */

(function($) {
    'use strict';

    // Dark Mode Toggle
    function initDarkMode() {
        const darkModeToggle = $('.dark-mode-toggle');
        const body = $('body');
        const darkModeStorageKey = 'grafixpointDarkMode';
        
        // Check if dark mode is enabled by default in theme settings
        const darkModeDefault = typeof grafixpointSettings !== 'undefined' && 
                               grafixpointSettings.darkModeDefault === true;
        
        // Check if user has a preference stored
        const userPreference = localStorage.getItem(darkModeStorageKey);
        
        // Set initial state based on user preference or default setting
        if (userPreference === 'true' || (userPreference === null && darkModeDefault)) {
            body.addClass('dark-mode');
            updateDarkModeToggleText(true);
        } else {
            body.removeClass('dark-mode');
            updateDarkModeToggleText(false);
        }
        
        // Toggle dark mode on click
        darkModeToggle.on('click', function() {
            const isDarkMode = body.hasClass('dark-mode');
            
            if (isDarkMode) {
                body.removeClass('dark-mode');
                localStorage.setItem(darkModeStorageKey, 'false');
                updateDarkModeToggleText(false);
            } else {
                body.addClass('dark-mode');
                localStorage.setItem(darkModeStorageKey, 'true');
                updateDarkModeToggleText(true);
            }
        });
        
        // Update toggle text based on current state
        function updateDarkModeToggleText(isDarkMode) {
            const toggleText = $('.dark-mode-toggle-text');
            const toggleIcon = $('.dark-mode-toggle-icon');
            
            if (isDarkMode) {
                toggleText.text('Switch to Light Mode');
                toggleIcon.html('☀️');
            } else {
                toggleText.text('Switch to Dark Mode');
                toggleIcon.html('🌙');
            }
        }
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        initDarkMode();
        // Other initializations would go here
    });
    
})(jQuery);
