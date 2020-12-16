
//version: 1.0
jQuery(function($) {
    $(document).ready(function(){
        console.log('locked and loaded 2');
        // Set the recurring type labels 
        var recurring_period_label = $('input.give-recurring-period').data('period-label');
        var recurring_period = $('input.give-recurring-period').data('period');

        $('.hf-recurring-period-label').text(recurring_period_label);
        $('.hf-recurring-period').text(recurring_period);

        $('button.donation-type').on('click', function(e) {
            e.preventDefault();
            $('.donation-type').removeClass('selected');
            $(this).addClass('selected');
            if($(this).hasClass('recurring')) {
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
        // check the fee recovery box
        // $('.give_fee_mode_checkbox').click();


        // Show and hide the tribute boxes
        $('.give-tributes-dedicate-donation legend').on('click', function(){
          if($(this).parent().hasClass('tribute-open')) {
            console.log($('.give-tributes-dedicate-donation>div'));
            var has_run = false;
            $('.give-tributes-dedicate-donation>div').slideUp(function() {
              if( !has_run ) {
                $(this).parent().removeClass('tribute-open');
                setTimeout(function(){
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

        // Disable the advance button 
        // $('.give-section.choose-amount .give-btn.advance-btn').prop('disabled', true);


        // Add a wrapper around it
        $('.give-section.choose-amount .give-btn.advance-btn').wrap('<div class="hf-advance-btn-wrapper"></div>');
        
        $('.hf-advance-btn-wrapper').on('click', function(e){
          if(e.target != this) {
            return;
          }
          var recurring_period_checkbox = $(this).parent().find('.give-recurring-donors-choice .give-recurring-period');
          if(!$(recurring_period_checkbox).prop('checked')) {
            var current_give_amount = $(this).parent().find('#give-amount');
            // Get the amount in the field
            var amount_entered = Number($(current_give_amount).val());
            // var message;
            if(amount_entered <= 150) {
              // message = 'Would you like to split this into four payments?';
              var new_amount = Math.trunc(amount_entered / 4);
              showEDSModal(this, amount_entered, new_amount);
            } else {
              // Remove the button disablement 
              $(this).find('.advance-btn').click();
            }
          } else {
            // Remove the button disablement 
            $(this).find('.advance-btn').click();
          }

        });

        // Handle the click when someone wants to keep their original donation amount
        $('.hf-donate-keep-onetime').on('click', function(e){
          e.preventDefault();
          var current_form = $(this).parentsUntil('.give-section.choose-amount').parent().find('.give-btn.advance-btn').click();
        });

        // Handle the click when someone wants to do recurring
        $('.hf-donate-modify').on('click', function(e) {
            e.preventDefault();
            var current_section = $(this).parentsUntil('.give-section.choose-amount').parent();
            // get the new recurring amount
            var recurring_amount = $(this).parentsUntil('.hf-modal').parent().data('recurring-amount');
       
            // set the value
            $('#give-amount').val(recurring_amount);
            // check the box 
            console.log('arpare');
           $(this).parentsUntil('.give-section.choose-amount').parent().find('.donation-type.recurring').click();
          //  $(current_section).find('.give-btn.advance-btn').click();
           
        });

        // Show the lightbox
        // $('.give-section.choose-amount .give-btn.advance-btn').on('click', function(event) {
        //   console.log('clicked');
        //     event.preventDefault();
        //     showEDSModal(this);
        // });
    
        //Remove modal on close or clicking outside the box
        $('body').on('click', '.hf-modal-background',function(){
            hideDSModal(this);
        });
    
        $('body').on('click', '.hf-close',function(){
            hideDSModal(this);
        });
        
    }); // end document ready
    function showEDSModal(current_element, original_amount, new_amount){

        // get the form currency symbol
        var currency_symbol = $(current_element).parentsUntil('.give-form').parent().data('currency_symbol');
        var element_lightbox = $(current_element).parent().find('.hf-modal');

        var original_amount_text = $(element_lightbox).find('.hf-donate-original-amount').text(currency_symbol + original_amount);
        var new_amount_text = $(element_lightbox).find('.hf-donate-new-amount').text(currency_symbol + new_amount);

        $(element_lightbox).data('recurring-amount', new_amount);

        $(element_lightbox).addClass('hf-modal-show-bg');
        // $('.hf-lightbox-content').text(message);
    } // end function showEDSModal
    
    function hideDSModal(current_element){
        console.log($(current_element));
        var element_lightbox = $(current_element).closest('.hf-modal');
        $(element_lightbox).removeClass('hf-modal-show-bg');
    
    } // end function hideDSModal
});  