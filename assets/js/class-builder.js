/**
 * Webflow-like Class Builder for WordPress
 * Interactive class management system
 */

(function($) {
    'use strict';
    
    // Global variables
    let selectedClasses = [];
    let previewHtml = '<div>Sample Element</div>';
    
    // Initialize when document is ready
    $(document).ready(function() {
        initClassBuilder();
    });
    
    /**
     * Initialize the class builder
     */
    function initClassBuilder() {
        // Bind class button clicks
        $('.class-btn').on('click', function() {
            const className = $(this).data('class');
            toggleClass(className);
            updateClassDisplay();
            updatePreview();
        });
        
        // Initialize preview
        updatePreview();
    }
    
    /**
     * Toggle a class (add/remove)
     */
    function toggleClass(className) {
        const index = selectedClasses.indexOf(className);
        
        if (index > -1) {
            // Remove class
            selectedClasses.splice(index, 1);
            $(`.class-btn[data-class="${className}"]`).removeClass('active');
        } else {
            // Add class
            selectedClasses.push(className);
            $(`.class-btn[data-class="${className}"]`).addClass('active');
        }
    }
    
    /**
     * Update the class display
     */
    function updateClassDisplay() {
        const classesString = selectedClasses.join(' ');
        $('#classes-input').val(classesString);
        
        // Update selected classes list
        const $list = $('#selected-classes-list');
        $list.empty();
        
        selectedClasses.forEach(function(className) {
            const $tag = $(`
                <span class="class-tag">
                    ${className}
                    <button class="remove-class" data-class="${className}">×</button>
                </span>
            `);
            
            $tag.find('.remove-class').on('click', function() {
                toggleClass(className);
                updateClassDisplay();
                updatePreview();
            });
            
            $list.append($tag);
        });
    }
    
    /**
     * Update the preview
     */
    function updatePreview() {
        if (selectedClasses.length === 0) {
            $('#class-preview').html('<div class="preview-placeholder">No classes selected</div>');
            return;
        }
        
        // Show loading
        $('#class-preview').html('<div class="preview-loading">Generating preview...</div>');
        
        // Send AJAX request
        $.ajax({
            url: classBuilderData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'class_builder_preview',
                nonce: classBuilderData.nonce,
                classes: selectedClasses.join(' '),
                html: previewHtml
            },
            success: function(response) {
                if (response.success) {
                    // Apply CSS to preview
                    const $preview = $('#class-preview');
                    $preview.empty();
                    
                    // Add CSS
                    if (response.data.css) {
                        $preview.append(`<style>${response.data.css}</style>`);
                    }
                    
                    // Add HTML
                    $preview.append(response.data.preview_html);
                } else {
                    $('#class-preview').html('<div class="preview-error">Error generating preview</div>');
                }
            },
            error: function() {
                $('#class-preview').html('<div class="preview-error">Error generating preview</div>');
            }
        });
    }
    
    /**
     * Get class information
     */
    function getClassInfo(className) {
        for (const category in classBuilderData.classes) {
            if (classBuilderData.classes[category][className]) {
                return classBuilderData.classes[category][className];
            }
        }
        return null;
    }
    
    /**
     * Show class tooltip
     */
    function showClassTooltip($element, className) {
        const classInfo = getClassInfo(className);
        if (!classInfo) return;
        
        const tooltip = $(`
            <div class="class-tooltip">
                <strong>${className}</strong>
                <p>${classInfo.description}</p>
                <div class="css-preview">
                    <code>${classInfo.css}</code>
                </div>
            </div>
        `);
        
        $element.append(tooltip);
        
        // Position tooltip
        const elementPos = $element.offset();
        tooltip.css({
            position: 'absolute',
            top: elementPos.top - tooltip.outerHeight() - 10,
            left: elementPos.left
        });
    }
    
    /**
     * Hide class tooltip
     */
    function hideClassTooltip() {
        $('.class-tooltip').remove();
    }
    
    // Bind tooltip events
    $(document).on('mouseenter', '.class-btn', function() {
        const className = $(this).data('class');
        showClassTooltip($(this), className);
    });
    
    $(document).on('mouseleave', '.class-btn', function() {
        hideClassTooltip();
    });
    
    /**
     * Copy classes to clipboard
     */
    function copyClassesToClipboard() {
        const classesString = selectedClasses.join(' ');
        navigator.clipboard.writeText(classesString).then(function() {
            // Show success message
            const $message = $('<div class="copy-success">Classes copied to clipboard!</div>');
            $('body').append($message);
            
            setTimeout(function() {
                $message.remove();
            }, 2000);
        });
    }
    
    // Bind copy button
    $(document).on('click', '#copy-classes', function() {
        copyClassesToClipboard();
    });
    
    /**
     * Clear all classes
     */
    function clearAllClasses() {
        selectedClasses = [];
        $('.class-btn').removeClass('active');
        updateClassDisplay();
        updatePreview();
    }
    
    // Bind clear button
    $(document).on('click', '#clear-classes', function() {
        clearAllClasses();
    });
    
    /**
     * Save classes to post meta
     */
    function saveClassesToPost() {
        const classesString = selectedClasses.join(' ');
        
        // Add hidden input to form
        if ($('#selected-classes-input').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'selected-classes-input',
                name: 'selected_classes',
                value: classesString
            }).appendTo('#post');
        } else {
            $('#selected-classes-input').val(classesString);
        }
    }
    
    // Bind save to post
    $(document).on('click', '#save-to-post', function() {
        saveClassesToPost();
        
        // Show success message
        const $message = $('<div class="save-success">Classes saved to post!</div>');
        $('body').append($message);
        
        setTimeout(function() {
            $message.remove();
        }, 2000);
    });
    
    /**
     * Load classes from post meta
     */
    function loadClassesFromPost() {
        const savedClasses = $('#selected-classes-input').val();
        if (savedClasses) {
            selectedClasses = savedClasses.split(' ').filter(c => c.trim() !== '');
            selectedClasses.forEach(className => {
                $(`.class-btn[data-class="${className}"]`).addClass('active');
            });
            updateClassDisplay();
            updatePreview();
        }
    }
    
    // Load classes on page load
    $(document).ready(function() {
        loadClassesFromPost();
    });
    
    /**
     * Search classes
     */
    function searchClasses(query) {
        $('.class-btn').each(function() {
            const className = $(this).data('class');
            const description = getClassInfo(className)?.description || '';
            
            if (className.toLowerCase().includes(query.toLowerCase()) || 
                description.toLowerCase().includes(query.toLowerCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    // Bind search
    $(document).on('input', '#class-search', function() {
        const query = $(this).val();
        searchClasses(query);
    });
    
    /**
     * Filter by category
     */
    function filterByCategory(category) {
        if (category === 'all') {
            $('.class-buttons').show();
        } else {
            $('.class-buttons').hide();
            $(`.class-buttons[data-category="${category}"]`).show();
        }
    }
    
    // Bind category filter
    $(document).on('click', '.category-filter', function() {
        const category = $(this).data('category');
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        filterByCategory(category);
    });
    
})(jQuery); 