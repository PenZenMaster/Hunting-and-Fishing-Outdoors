
/*
    * Property Actions Script
    * Handles AJAX requests for property actions like duplicate, featured, etc.
    * Updates the UI based on the response from the server.
    * 
    * @package wprentals-core
    * @since 1.0.0
*/

(function ($) {
    'use strict';

    $(document).ready(function () {
        $(document).on('click', '.wprentals_properties_action_admin', function (e) {
            e.preventDefault();

            var $button = $(this);
            var action = $button.data('action');
            var postId = $button.data('postid');
            var currentButtons  = $button.parents('.wpestate_admin_actions_wrapper').html();

            if (action && postId) {
                $button.addClass('wprentals_actions_loader');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'wp_estate_handle_property_action',
                        action_type: action,
                        post_id: postId,
                        _wpnonce: wprentalsPropertyActions.nonce
                    },
                    success: function (response) {
                        $button.removeClass('wprentals_actions_loader');
                        if (response.success) {
                            // Update the buttons with the new state
                            if ( action === 'duplicate' ) {
                                // Refresh the page if the action was duplicate
                                location.reload();
                            }
                            if ( action == 'featured' ) {
                                $button.parents('.type-estate_property').find('.estate_featured').text(response.data.featured_text);
                            }
                            $button.parents('.type-estate_property').find('.status_label').text(response.data.status_text);
                            $button.parents('.type-estate_property').find('.status_label').attr('class', 'status_label ' + response.data.status_class);
                            $button.parents('.wpestate_admin_actions_wrapper').html(response.data.buttons);
                        } else {
                            // If the action failed, revert to the original buttons and show an error message
                            $button.parents('.wpestate_admin_actions_wrapper').html(currentButtons);

                            $('<div class="error-message"></div>')
                                .text(response.data.message || 'An error occurred.')
                                .appendTo($button.parents('.wpestate_admin_actions_wrapper'));
                        }
                    },
                    error: function () {
                        // If the AJAX request fails, revert to the original buttons and show an error message
                        $button.parents('.wpestate_admin_actions_wrapper').html(currentButtons);
                        $button.removeClass('wprentals_actions_loader');
                        $('<div class="error-message"></div>')
                            .text('An error occurred. Please try again.')
                            .appendTo($button.parents('.wpestate_admin_actions_wrapper'));
                    }
                });
            }
        });
    });

})(jQuery);