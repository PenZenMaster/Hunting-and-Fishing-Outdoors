/**
 * Admin scripting that powers the Studio template meta box location controls.
 *
 * Ported from the Residence Studio plugin and adapted for WpRentals specific
 * taxonomies so administrators can add include/exclude rows with dynamic term
 * dropdowns.
 */

jQuery(document).ready(function (jQuery) {
    function attachDeleteHandlers(scope) {
        scope.find('.wpestate-remove-location').off('click').on('click', function (e) {
            e.preventDefault();
            jQuery(this).closest('.wpestate-location-row').remove();
        });
    }

    function filterTaxOptions(row) {
        var taxonomy = row.find('.wpestate-selection_dropdown option:selected').data('taxonomy');
        var termSelect = row.find('.wpestate-tax-term');

        if (!termSelect.length) {
            return;
        }

        termSelect.find('option').each(function () {
            var optTax = jQuery(this).data('taxonomy');

            if (jQuery(this).hasClass('hidden-by-template')) {
                jQuery(this).hide();
            } else if (!taxonomy || !optTax || jQuery(this).val() === '' || taxonomy === optTax) {
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        });

        if (termSelect.find('option:selected').css('display') === 'none') {
            termSelect.val('');
        }
    }

    attachDeleteHandlers(jQuery('#wpestate-location-container'));
    attachDeleteHandlers(jQuery('#wpestate-exclude-location-container'));

    jQuery('#wpestate-add-location').on('click', function (e) {
        e.preventDefault();
        var container = jQuery('#wpestate-location-container');
        var firstRow = container.find('.wpestate-location-row').first();
        if (firstRow.length) {
            var clone = firstRow.clone();
            var selects = clone.find('select');
            if (selects.length) {
                selects.first().val('disabled');
                if (selects.length > 1) {
                    selects.last().val('');
                }
            }
            container.append(clone);
            attachDeleteHandlers(clone);
            filterTaxOptions(clone);
        }
    });

    jQuery('#wpestate-add-exclude-location').on('click', function (e) {
        e.preventDefault();
        var container = jQuery('#wpestate-exclude-location-container');
        var firstRow = container.find('.wpestate-location-row').first();
        if (firstRow.length) {
            var clone = firstRow.clone();
            var selects = clone.find('select');
            if (selects.length) {
                selects.first().val('disabled');
                if (selects.length > 1) {
                    selects.last().val('');
                }
            }
            container.append(clone);
            attachDeleteHandlers(clone);
            filterTaxOptions(clone);
        }
    });

    jQuery(document).on('change', '.wpestate-selection_dropdown', function () {
        var row = jQuery(this).closest('.wpestate-location-row');
        filterTaxOptions(row);
    });

    jQuery('.wpestate-location-row').each(function () {
        filterTaxOptions(jQuery(this));
    });


    jQuery('#template-type-select').change(function() {
        // Get data-template from selected option
        var selectedTemplate = jQuery(this).find('option:selected').data('template');

        // Loop through all location dropdown options
        jQuery('.wpestate-selection_dropdown option').each(function() {
            var optionTemplate = jQuery(this).data('template');
            var hasTaxonomy  = jQuery(this).data('taxonomy');

            if (selectedTemplate === 'category') {
                if (hasTaxonomy || jQuery(this).val() === 'estate_property_all_taxonomies') {
                    jQuery(this).show();
                } else {
                    jQuery(this).hide();
                }
            } else if (!selectedTemplate || optionTemplate === selectedTemplate || !optionTemplate) {
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        });

        // Loop through taxonomy term dropdown options
        jQuery('.wpestate-tax-term option').each(function() {
            if (jQuery(this).val() === '') {
                jQuery(this).removeClass('hidden-by-template').show();
                return;
            }

            var optionTemplate = jQuery(this).data('template');

            if (!selectedTemplate || selectedTemplate === 'category') {
                jQuery(this).removeClass('hidden-by-template');
            } else if (optionTemplate === selectedTemplate) {
                jQuery(this).removeClass('hidden-by-template');
            } else {
                jQuery(this).addClass('hidden-by-template').hide();
            }
        });

        jQuery('.wpestate-tax-term').each(function() {
            var select = jQuery(this);
            if (select.find('option:selected').hasClass('hidden-by-template')) {
                select.val('');
            }
        });



        // Now handle optgroups
        jQuery('.wpestate-selection_dropdown optgroup').each(function() {
                var allOptionsHidden = true;

                jQuery(this).children('option').each(function() {
                    if (jQuery(this).css('display') !== 'none') {
                 
                        allOptionsHidden = false;
                
                    }
                });

                if (allOptionsHidden) {
                    jQuery(this).hide();
                } else {
                    jQuery(this).show();
                }
        });

        // Refresh term dropdowns visibility based on filtered taxonomies
        jQuery('.wpestate-location-row').each(function(){
            filterTaxOptions(jQuery(this));
        });





    });
    


    
    // Run on page load
    jQuery('#template-type-select').trigger('change');
});
