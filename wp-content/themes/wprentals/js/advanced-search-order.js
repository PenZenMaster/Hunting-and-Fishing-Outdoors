jQuery(function () {
    jQuery(document).on('click', '#filter_order li', function (event) {
        event.preventDefault();
        var pick, value, parent, args, page_id, ajaxurl, nonce;
        pick = jQuery(this).text();
        value = jQuery(this).attr('data-value');
        parent = jQuery(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_filter"></span>').attr('data-value', value);

        if (jQuery('body').hasClass('is_half_map')) {
            if (typeof wpestate_start_filtering_ajax_map === 'function') {
                wpestate_start_filtering_ajax_map(1);
            }
            return;
        }

        ajaxurl = ajaxcalls_vars.admin_url + 'admin-ajax.php';
        args = jQuery('#searcharg').val();
        page_id = jQuery('#page_idx').val();
      //  jQuery('#listing_ajax_container').empty();
       // jQuery('#listing_loader').show();
        nonce = jQuery('#wpestate_search_nonce').val();

        const listingContainer = jQuery('#listing_ajax_container');
        wpestate_createSkeletons(listingContainer);



        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'wrentals_advanced_search_filters',
                'args': args,
                'value': value,
                'page_id': page_id,
                'security': nonce
            },
            success: function (data) {
                jQuery('#listing_loader').hide();
                jQuery('#listing_ajax_container').append(data);

                wpestate_replaceSkeletons(listingContainer, data);


                wpestate_restart_js_after_ajax();
                wpestate_add_pagination_orderby();
            },
            error: function (errorThrown) {
            }
        }); //end ajax
    });

    // Ensure pagination links keep the selected order on initial load
    wpestate_add_pagination_orderby();
});

/**
 * Append the currently selected order to pagination links so that
 * navigating between pages preserves the chosen sorting option.
 */
function wpestate_add_pagination_orderby() {
    var orderVal = jQuery('#a_filter_order').attr('data-value');
    if (typeof orderVal === 'undefined' || orderVal === '') {
        return;
    }

    jQuery('.pagination a').each(function () {
        var href = jQuery(this).attr('href');
        if (typeof href === 'undefined') {
            return;
        }

        // Remove any existing order_search parameter before adding a new one
        href = href.replace(/([?&])order_search=.*?(&|$)/, '$1').replace(/&$/, '');
        var separator = href.indexOf('?') === -1 ? '?' : '&';
        jQuery(this).attr('href', href + separator + 'order_search=' + orderVal);
    });
}

