//version: 1.5

// You can modify these variables to change what happens when someone 
// leaves the donation amount set to "One Time." 

// One time donation amount set to this amount or lower will trigger 
// the message:
var min_onetime_donation_amount = 150;

// This number is how much the one time donation amount will be 
// divided by to create a monthly amount. For example, if this
// is set to 4 and they had entered $100, the monthly amount
// will be $25:
var divide_onetime_donation_amount_by = 4;


jQuery(function($) {
    $(document).ready(function() {

        // Sets the labels appropriately on the tabs and in other places according
        // to the recurring period chosen. 
        var recurring_period_label = $('input.give-recurring-period').data('period-label');
        var recurring_period = $('input.give-recurring-period').data('period');

        $('.hf-recurring-period-label').text(recurring_period_label);
        $('.hf-recurring-period').text(recurring_period);

        // Handles the click on the donation tabs
        $('button.donation-type').on('click', function(e) {
            e.preventDefault();
            $('.donation-type').removeClass('selected');
            $(this).addClass('selected');
            if ($(this).hasClass('recurring')) {
                $('.heart-checkbox').prop("checked", true);
                $('.give-recurring-donors-choice .give-recurring-period').prop("checked", true);
                $('.donation-amount-period .one-time').fadeOut(function() {
                    $('.donation-amount-period .recurring').fadeIn();
                });
            } else {
                $('.heart-checkbox').prop("checked", false);
                $('.give-recurring-donors-choice .give-recurring-period').prop("checked", false);
                $('.donation-amount-period .recurring').fadeOut(function() {
                    $('.donation-amount-period .one-time').fadeIn();
                });
            }
        });


        // Show and hide the tribute boxes when the link is clicked
        $('.give-tributes-dedicate-donation legend').on('click', function() {
            if ($(this).parent().hasClass('tribute-open')) {
                var has_run = false;
                $('.give-tributes-dedicate-donation>div').slideUp(function() {
                    if (!has_run) {
                        $(this).parent().removeClass('tribute-open');
                        setTimeout(function() {
                            $(this).parent().find('.give-tributes-no').click();
                        }, 3000);
                    }
                });
            } else {
                $('.give-tributes-dedicate-donation>div').slideDown();
                $(this).parent().addClass('tribute-open')
                $(this).parent().find('.give-tributes-yes').click();
            }
        });

        // Add a wrapper around the donation button so we can catch events
        // and show a message if the person hasn't chosen to donate enough
        $('.give-section.choose-amount .give-btn.advance-btn').wrap('<div class="hf-advance-btn-wrapper"></div>');

        // Handle what happens when someone tries to click the donation button
        $('.hf-advance-btn-wrapper').on('click', function(e) {
            if (e.target != this) {
                return;
            }
            var recurring_period_checkbox = $(this).parent().find('.give-recurring-donors-choice .give-recurring-period');
            if (!$(recurring_period_checkbox).prop('checked')) {
                var current_give_amount = $(this).parent().find('#give-amount').val();
                // Get the amount in the field
                var currency_code = $(this).parentsUntil('.give-form').parent().attr('data-currency_code');
                if (currency_code == 'EUR') {
                    var amount_entered = Number(current_give_amount.replace(',', '.'));
                } else {
                    var amount_entered = Number(current_give_amount);
                }

                if (amount_entered <= min_onetime_donation_amount) {
                    var new_amount = Math.trunc(amount_entered / divide_onetime_donation_amount_by);
                    showEDSModal(this, current_give_amount, new_amount);
                } else {
                    // Continue to the payment form with no change
                    $(this).find('.advance-btn').click();
                }
            } else {
                // Continue to the payment form with no change
                $(this).find('.advance-btn').click();
            }
        });

        // Handle the click when someone wants to keep their original donation amount
        $('.hf-donate-keep-onetime').on('click', function(e) {
            e.preventDefault();
            $(this).parentsUntil('.give-section.choose-amount').parent().find('.give-btn.advance-btn').click();
        });

        // Handle the click when someone wants to do recurring donation instead
        $('.hf-donate-modify').on('click', function(e) {

            var donate_modify_target = e.target;
            e.preventDefault();
            var current_section = $(donate_modify_target).parentsUntil('.give-section.choose-amount').parent();

            // get the new recurring amount
            var recurring_amount = $(current_section).find('.hf-modal').data('recurring-amount');

            // change the amount - we have to focus in and out of the 
            // box to get GiveWP to recognize the new amount
            $(current_section).find('#give-amount').focus();
            $(current_section).find('#give-amount').val(recurring_amount);
            $(current_section).find('#give-amount').focusout();

            $(current_section).find('.donation-type.recurring').click();

            setTimeout(function() {
                // click the donation button and continue to payment 
                $(current_section).find('.give-btn.advance-btn').click();
            }, 750);

        });

        //Remove the lightbox on close or clicking outside the box
        $('body').on('click', '.hf-modal-background', function() {
            hideDSModal(this);
        });

        $('body').on('click', '.hf-close', function() {
            hideDSModal(this);
        });

    }); // end document ready
    function showEDSModal(current_element, original_amount, new_amount) {

        // get the form currency symbol
        var currency_symbol = $(current_element).parentsUntil('.give-form').parent().attr('data-currency_symbol');
        console.log(currency_symbol);
        var element_lightbox = $(current_element).parent().find('.hf-modal');

        $(element_lightbox).find('.hf-donate-original-amount').text(currency_symbol + original_amount);
        $(element_lightbox).find('.hf-donate-new-amount').text(currency_symbol + new_amount);

        $(element_lightbox).data('recurring-amount', new_amount);

        $(element_lightbox).addClass('hf-modal-show-bg');

    } // end function showEDSModal

    function hideDSModal(current_element) {
        var element_lightbox = $(current_element).closest('.hf-modal');
        $(element_lightbox).removeClass('hf-modal-show-bg');

    } // end function hideDSModal
});