jQuery(document).ready(function ($) {

    // $('.vw_half_mrng input[name="vw_mrng_time01"]').prop("checked", true);
    
    setTimeout(function () {
        $('.vw_half_mrng input[name="vw_mrng_time01"]').trigger('click');
    },200);

    $('input[name="vw_mrng_time01"]').on('click', function () {
        // Code to be executed when the value changes
        // You can access the changed value using $(this).val()
        var selectedOption = $(this).val();
        var $list = $('#start_hour_wrapper_list,#end_hour_wrapper_list');
        var startHour, endHour;
        if (selectedOption === 'Morning') {
            var startHour = $('#fah_start_hour01').val();
            var endHour = $('#fah_end_hour01').val();
            $('#vdf_bk_day01 .morning_price').removeClass('hide');
            $('#vdf_bk_day01 .afternoon_price').addClass('hide');

        } else if (selectedOption === 'Afternoon') {
            var startHour = $('#fah_start_hour01_noon').val();
            var endHour = $('#fah_end_hour01_noon').val();
            $('#vdf_bk_day01 .morning_price').addClass('hide');
            $('#vdf_bk_day01 .afternoon_price').removeClass('hide');

        }
        var in_date_front = jQuery('#start_date');
        var out_date_front = jQuery('#end_date');



        // $('[name=start_hour_input]').val(startHour);
        // $('#start_hour_no_wrapper').attr('data-value', startHour);
        // $('[name=end_hour_input]').val(endHour);
        // $('#end_hour_no_wrapper').attr('data-value', endHour);
        // book_from = $('#start_hour_no_wrapper').text(startHour);
        // book_to = $("#end_hour_no_wrapper").text(endHour);
        $('#start_hour_wrapper_list li[data-value="' + startHour + '"]').trigger('click');
        $('#end_hour_wrapper_list li[data-value="' + endHour + '"]').trigger('click');
        setTimeout(function () {
            $('#end_hour_wrapper_list li[data-value="' + endHour + '"]').trigger('click');
        }, 2000);
        // console.log(startHour, endHour);
        // $list.empty();
        // var startHour = parseInt(startHour.split(":")[0]);
        // var endHour = parseInt(endHour.split(":")[0]);
        // for (startHour; startHour <= endHour; startHour++) {
        //     $list.append('<li role="presentation" data-type="2" data-value="' + startHour + ':00">' + startHour + ':00' + '</li>');
        // }
        // alert('i am a live')
        // Add your desired actions here
    });

    if ($("#local_booking_type").length) {
        // Element with id "local_booking_type" exists
        console.log("Element exists");
        $("#local_booking_type").on('change', function () {
            var local_booking_type = $(this).find("option:selected").text();
            var local_booking_type_val = $(this).val();

            if (local_booking_type_val == '1') {
                $('.wqs_per_day').show();
                $('.wqs_half_day').hide();
                $('.wqs_per_hour').hide();	
                $('.wqs_price_per_unit').show();
                $('.wqs_extra_price_per_guest').show();
                $('.wqs_morning_price').hide();
                $('.wqs_afternoon_price').hide();
                

            } else if (local_booking_type == 'Half Day') {
                $('.wqs_per_day').hide();
                $('.wqs_half_day').hide();
                $('.wqs_per_hour').hide();
                $('.wqs_price_per_unit').hide();                
                if( $('#price_per_guest_from_one')[0].checked) {
                    $('.wqs_extra_price_per_guest').hide();
                } else {
                    $('.wqs_extra_price_per_guest').show();                                
                }
                $('.wqs_morning_price').show();
                $('.wqs_afternoon_price').show();

            } else if (local_booking_type == 'Per Hour') {
                $('.wqs_per_day').hide();
                $('.wqs_half_day').hide();
                $('.wqs_per_hour').show();					
                $('.wqs_price_per_unit').show();
                $('.wqs_extra_price_per_guest').show();
                $('.wqs_morning_price').hide();
                $('.wqs_afternoon_price').hide();
            }    
            // } else if (local_booking_type == 'Custom Date') {                
            //     $('.wqs_per_day').hide();
            //     $('.wqs_half_day').hide();
            //     $('.wqs_per_hour').hide();	
            //     $('.wqs_morning_price').hide();
            //     $('.wqs_afternoon_price').hide();
            //     $('.wqs_custom_date_section').show();
            // }
        });
        $("#local_booking_type").trigger('change');
    } else {
        // Element with id "local_booking_type" does not exist
        console.log("Element does not exist");
    }

    $('#price_per_guest_from_one').change(function() {

        var local_booking_type = $("#local_booking_type").val();
        if(local_booking_type == 2) {
            if( $('#price_per_guest_from_one')[0].checked) {            
                $('.wqs_extra_price_per_guest').hide();     
            } else {            
                $('.wqs_extra_price_per_guest').show();     
            }
        }
    });

    $('#filter_amenities input[type="checkbox"]').on('change', function () {

    });
    // Begin code change for amenity filter not working  
    function set_amenities_property() {
        var selectedValues = $('input[type="checkbox"].filter_amenities:checked').map(function () {
            return this.value;
        }).get();


        if (selectedValues.length === 0) {
            $('.listing_detail:not(.feature_block_Includes):not(.feature_block_Features):not(.feature_block_Basic) .waitem').show();
        } else {
            $('.listing_detail:not(.feature_block_Includes):not(.feature_block_Features):not(.feature_block_Basic) .waitem').hide();
            $.each(selectedValues, function (index, value) {
                $('.wqst_' + value).show();
            });
        }

    }
    set_amenities_property();
    $('.filter_amenities').on('change', function () {

        set_amenities_property();        
        
        // var selectedValue = $(this).val();

        // if (selectedValue === '') {
        //     $('.listing_detail:not(.feature_block_Includes):not(.feature_block_Features):not(.feature_block_Basic) .waitem').show();
        // } else {
        //     $('.listing_detail:not(.feature_block_Includes):not(.feature_block_Features):not(.feature_block_Basic) .waitem').hide();
        //     $('.wqst_' + selectedValue).show();
        // }
    });
    // End code change for amenity filter not working

    $('input[name="booking_repeat_event_type"]').on('change', function () {
        var event_val = $('input[name="booking_repeat_event_type"]:checked').val();
        if (event_val == 'daily') {
            $('.repeat-event-type-label').text('day(s)');
            $('.repeat-week-day').removeClass('active');

        } else {
            $('.repeat-event-type-label').text('week(s)');
            $('.repeat-week-day').addClass('active');
        }



    });

    // ========================================
    // New Amenity Popup Handler
    // Added: 2026-02-13 (missing from original code)
    // ========================================

    $('.new-amenity-pop-up, .vd-add-new_amenity').on('click', function(e) {
        e.preventDefault();

        // Create modal if it doesn't exist
        if ($('#new-amenity-modal').length === 0) {
            var modalHTML = '<div id="new-amenity-modal" class="new-amenity-modal-overlay" style="display:none;">' +
                '<div class="new-amenity-modal-container">' +
                    '<div class="new-amenity-modal-header">' +
                        '<h3>Request New Amenity</h3>' +
                        '<button class="new-amenity-modal-close">&times;</button>' +
                    '</div>' +
                    '<div class="new-amenity-modal-body">' +
                        '<div class="amenity-form-wrapper">' +
                            // Try to load Elementor form
                            '<?php echo do_shortcode("[elementor-template id=\"3750\"]"); ?>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

            $('body').append(modalHTML);

            // Add modal styles
            if ($('#new-amenity-modal-styles').length === 0) {
                var modalStyles = '<style id="new-amenity-modal-styles">' +
                    '.new-amenity-modal-overlay {' +
                        'position: fixed;' +
                        'top: 0;' +
                        'left: 0;' +
                        'width: 100%;' +
                        'height: 100%;' +
                        'background: rgba(0, 0, 0, 0.7);' +
                        'z-index: 99999;' +
                        'display: flex;' +
                        'align-items: center;' +
                        'justify-content: center;' +
                    '}' +
                    '.new-amenity-modal-container {' +
                        'background: white;' +
                        'border-radius: 8px;' +
                        'max-width: 600px;' +
                        'width: 90%;' +
                        'max-height: 90vh;' +
                        'overflow-y: auto;' +
                        'box-shadow: 0 4px 20px rgba(0,0,0,0.3);' +
                    '}' +
                    '.new-amenity-modal-header {' +
                        'padding: 20px;' +
                        'border-bottom: 1px solid #ddd;' +
                        'display: flex;' +
                        'justify-content: space-between;' +
                        'align-items: center;' +
                    '}' +
                    '.new-amenity-modal-header h3 {' +
                        'margin: 0;' +
                        'font-size: 24px;' +
                    '}' +
                    '.new-amenity-modal-close {' +
                        'background: none;' +
                        'border: none;' +
                        'font-size: 32px;' +
                        'cursor: pointer;' +
                        'color: #999;' +
                        'line-height: 1;' +
                        'padding: 0;' +
                        'width: 32px;' +
                        'height: 32px;' +
                    '}' +
                    '.new-amenity-modal-close:hover {' +
                        'color: #333;' +
                    '}' +
                    '.new-amenity-modal-body {' +
                        'padding: 20px;' +
                    '}' +
                '</style>';

                $('head').append(modalStyles);
            }

            // Close button handler
            $(document).on('click', '.new-amenity-modal-close, .new-amenity-modal-overlay', function(e) {
                if (e.target === this) {
                    $('#new-amenity-modal').fadeOut(300);
                }
            });

            // Close on ESC key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' && $('#new-amenity-modal').is(':visible')) {
                    $('#new-amenity-modal').fadeOut(300);
                }
            });
        }

        // Show the modal
        $('#new-amenity-modal').fadeIn(300);
    });

});

