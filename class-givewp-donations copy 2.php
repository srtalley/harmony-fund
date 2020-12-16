<?php 
/**
 * PHP class to handle modifications to the donations
 */


namespace HarmonyFund;
use Give\Helpers\Form\Template as FormTemplateUtils;

class HarmonyFundGiveWPDonations {

  
    public function __construct() {
      add_filter( 'give_form_content_output', array($this, 'hf_give_form_content_output'), 10, 3);
      add_action( 'give_before_donation_levels', array($this, 'hf_show_give_monthly_tab'), 10, 2); 
    } // end function construct

    public function hf_give_form_content_output($output, $form_id, $args) {

      if(give_is_setting_enabled( give_get_meta( $form_id, '_give_display_content', true ))) {
        // make sure it's not null 
        if(strpos($output, 'give_pre_form-content"></div>') === false){

          $hf_output  = '<div id="hf-give-form-content-wrap">';
          $hf_output .= '<div class="hf-give-form-featured-image" style="background-image: url(\'' . wp_get_attachment_url( get_post_thumbnail_id($form_id) ) . '\');">' . get_the_post_thumbnail($form_id) . '</div>';
          
          $hf_output .= '<div class="hf-content-wrap">' . $output . '</div>';

          $hf_output .= '</div>'; // #hf-give-form-content-wrap
          return $hf_output;
        }
      } 

    
    }
    public function hf_show_give_monthly_tab($output, $form) {
      wl('colors');
      // Localize Template options
      // namespace Give\Views\Form\Templates\Sequoia;
          $templateOptions = FormTemplateUtils::getOptions();
          $primary_color        = ! empty( $templateOptions['introduction']['primary_color'] ) ? $templateOptions['introduction']['primary_color'] : '#28C77B';
          wl($primary_color);
        ?>

<script>
   
//version: 1.0
jQuery(function($) {
    $(document).ready(function(){
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
        $('.give_fee_mode_checkbox').click();


        $('.give-display-onpage .give-tributes-dedicate-donation legend').on('click', function(){
          if($(this).parent().hasClass('tribute-open')) {
            $('.give-display-onpage .give-tributes-dedicate-donation>div').slideUp(function() {
              $(this).parent().removeClass('tribute-open');
              $(this).parent().find('.give-tributes-no').click();
            });

          } else {
            $('.give-display-onpage .give-tributes-dedicate-donation>div').slideDown();
            $(this).parent().addClass('tribute-open')
            $(this).parent().find('.give-tributes-yes').click();
          }

        });
    });
});  
</script>
<style>

/* DONATION BUTTONS */

.donation-type-selector {
    /* box-sizing: border-box; */
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    /* margin: 0 30px 0 !important; */
}
.donation-type-selector .donation-type {
    flex: 1 1 50%;
    /* background-color: gray; */
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
    color: #515151;
    background: #fff;
    background-image: -webkit-linear-gradient(top,#fff 0,#f9f9f9 100%);
    background-image: -o-linear-gradient(top,#fff 0,#f9f9f9 100%);
    background-image: linear-gradient(to bottom,#fff 0,#f9f9f9 100%);
    background-repeat: repeat-x;
    display: block;
    width: 100%;
    padding: 0;
    line-height: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
    border: 0;
    margin: 0;
    outline: 0;
    text-align: center;
    height: 50px;
    font-family: 'Montserrat', Arial, Helvetica, sans-serif;
    letter-spacing: 0.5px;
    position: relative;
}
.donation-type-selector .donation-type:hover {
  background: #f7f7f7;
  color: #515151;
}
.donation-type-selector .donation-type .button-gradient {
    opacity: 0;
    -webkit-transition: opacity 50ms ease-in-out;
    -o-transition: opacity 50ms ease-in-out;
    transition: opacity 50ms ease-in-out;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}
.donation-type-selector .donation-type.selected .button-gradient {
    opacity: 1;
    background: #2f7fb5;
    background-image: -webkit-linear-gradient(top,#368fcc 0,#2f7fb5 100%);
    background-image: -o-linear-gradient(top,#368fcc 0,#2f7fb5 100%);
    background-image: linear-gradient(to bottom,#368fcc 0,#2f7fb5 100%);
    background-repeat: repeat-x;
}
.donation-type-selector .donation-type.one-time .button-gradient {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}
.donation-type-selector .donation-type.recurring .button-gradient {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}
.donation-type-selector .donation-type .button-text {
    position: relative;
    z-index: 2;
    display: inline-block;
}
.donation-type-selector .donation-type.selected .button-text {
    color: #fff;
}

/* Move the warning down */
.give_error.give_warning {
  order: 100;
}

/* SINGLE PAGE FORM */


.give-form-wrap.give-display-onpage * {
  box-sizing: border-box;
}
.give-form-wrap.give-display-onpage p {
  margin-bottom: 10px;
}
/* Hide the general title */
.give-display-onpage .give-form-title {
  display: none;
} 
.give-form-wrap.give-display-onpage {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
    margin: 0 -2%;
}
.give-form-wrap.give-display-onpage .give-form-title,
.give-form-wrap.give-dispaly-onpage .give_error.give_warning {
    flex: 1 1 100%;
}
.give-display-onpage [id*=give-form] .give-recurring-donors-choice {
    display: none;
}
.give-form-wrap.give-display-onpage #hf-give-form-content-wrap {
    flex: 1 1 46%;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    border-radius: 20px;
    box-shadow: 1px 1px 10px 4px rgb(0 0 0 / 40%);
    overflow: hidden;
    min-width: 400px;
    margin: 0 1% 40px;
}
.give-display-onpage .hf-give-form-featured-image {
    /* flex: 1 1 100%; */
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.give-display-onpage .hf-give-form-featured-image img {
    max-width: 100%;
    visibility: hidden;
}
.give-display-onpage p, 
.give-display-onpage li,
.give-display-onpage label {
  font-size: 16px;
}
.give-display-onpage h3 {
  font-size: 18px !important;
  color: inherit !important;
  margin-bottom: 10px;
}
.give-display-onpage .give-label {
  padding: 0;
}
.give-display-onpage input,
.give-display-onpage input.required {
  font-size: 18px;
  background: #fafafa;
  font-family: "Montserrat",sans-serif;
  font-weight: 500;
  transition: color .1s ease-in-out,background-color .1s ease-in-out;
  vertical-align: baseline;
  line-height: 1.5;
}
.give-display-onpage .hf-content-wrap {
  padding: 20px;
}
.give-display-onpage .hf-content-wrap div.give-form-content-wrap {
  margin: 0;
}
.give-form-wrap.give-display-onpage .give-form  {
    flex: 1 1 48%;
    border-radius: 20px;
    box-shadow: 1px 1px 10px 4px rgb(0 0 0 / 40%);
    overflow: hidden;
    padding: 20px;
    margin: 0;
    display: flex;
    flex-direction: column;
    margin: 0 1% 40px;
}
.give-display-onpage .hf-give-form-title {
  text-align: center;
}

.give-display-onpage #give-donation-level-button-wrap {
    order: 5;
}

.give-display-onpage .give-total-wrap {
    order: 10;
}

.give-display-onpage .give-fee-recovery-donors-choice.give-fee-message {
    order: 20
}
.give-display-onpage .donation-amount-period {
    order: 30;
}

.give-display-onpage .give-tributes-dedicate-donation {
    order: 25;
}
.give-display-onpage #give-payment-mode-select {
  order: 25;
}
.give-display-onpage #give_purchase_form_wrap {
  order: 25;
}
.give-display-onpage #give-donation-level-button-wrap {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  margin: 15px -10px 0;
}
.give-display-onpage #give-donation-level-button-wrap li {
  flex: 1 1 28%;
  padding: 0 10px 10px;
  margin-right: 0;
  min-height: 44px;
  display: flex;
}
.give-display-onpage #give-donation-level-button-wrap li .give-btn-level-custom {
  font-size: 14px;
  line-height: 15px;
  padding: 2px 8px;
}
.give-display-onpage .give-btn {
  color: #515151;
  background: #fff;
  background-image: -webkit-linear-gradient(top,#fff 0,#f9f9f9 100%);
  background-image: -o-linear-gradient(top,#fff 0,#f9f9f9 100%);
  background-image: linear-gradient(to bottom,#fff 0,#f9f9f9 100%);
  padding: 8px 10px;
  cursor: pointer;
  line-height: 22px;
  font-size: 20px;
  border-radius: 5px;
  box-shadow: 1px 1px 5px 2px rgb(0 0 0 / 16%);
  width: 100%;
  height: 100%;
  letter-spacing: 1px;

}
.give-display-onpage .give-btn:hover {
  background: #f7f7f7;
  color: #515151;
}
.give-display-onpage .give-donation-amount {
  display: flex;
  flex-direction: row;
}
.give-display-onpage .give-donation-amount .give-cs-select-currency.give-cs-mini-dropdown {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}
.give-display-onpage .give-donation-amount .give-currency-symbol.give-currency-position-before {
  height: 50px;
  display: flex;
  flex-direction: row;
  align-items: center;
  font-size: 24px;
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}
.give-display-onpage .give-donation-amount input#give-amount {
  flex: 1 1 auto;
  text-align: center;
  font-size: 36px;
    line-height: 50px;
    height: 50px;
    font-weight: 400;
    vertical-align: middle;
    color: #348ac5;
    padding: 1px 7px;
    margin: 0;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;

}
.give-display-onpage fieldset.give-currency-switcher-wrap.form-row.form-row-wide {
    order: 10;
    font-size: 15px;
    text-align: center;
    margin: 0 !important;
}
.give-display-onpage .give-currency-switcher-msg-wrap.give-hidden {
    margin-bottom: 10px;
}
.give-display-onpage .give-fee-message-label {
  padding: 0;
}
.give-display-onpage .give-fee-message-label input[type="checkbox"] {
  width: 32px;
  height: 32px;
  order: 1;
  z-index: 2;
  position: absolute;
  right: 30px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  visibility: hidden;
} 
.give-display-onpage .give-fee-message-label .give-fee-message-label-text {
  font-size: 18px;
  padding: 5px 10px;
  box-shadow: 1px 1px 3px 1px rgb(0 0 0 / 30%);
  text-align: center;
  border-radius: 5px;
  position: relative;
  width: 100%;
  display: block;
  transition: all 0.4s ease-out;
}

.give-display-onpage .give-fee-message-label .give-fee-message-label-text:before {
  width: 20px;
    height: 20px;
    border-radius: 50%;
    content: "";
    background-color: #bd4646;
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translate(-50%,-50%) scale3d(1,1,1);
    transition: all 300ms cubic-bezier(.4,0,.2,1);
    opacity: 0;
    z-index: -1;
}

.give-display-onpage .give-fee-message-label .give-fee-message-label-text:after {

  width: 22px;
  height: 22px;
  content: "";
  border: 2px solid #d1d7dc;
  background-color: #fff;
  background-repeat: no-repeat;
  background-position: -1px 0px;
  border-radius: 50%;
  z-index: 2;
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  transition: all 200ms ease-in;
}
.give-display-onpage .give-fee-message-label input[type="checkbox"]:checked ~ .give-fee-message-label-text {
  /* color: #fff;  */
  border-color: #dddddd;
  /* font-weight: 600; */
  color: #fff;
  background-color: #348ac5;

}
.give-display-onpage .give-fee-message-label input[type="checkbox"]:checked ~ .give-fee-message-label-text:before {
  transform: translate(-50%, -50%) scale3d(56, 56, 1);
  opacity: 1;
}
.give-display-onpage .give-fee-message-label input[type="checkbox"]:checked ~ .give-fee-message-label-text:after {
  background-color: #fff;
  border-color: #d1d7dc;
  background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23348ac5' fill-rule='nonzero'/%3E%3C/svg%3E ");
}

.give-display-onpage .give-tributes-dedicate-donation {
  margin-bottom: 0 !important;
}
.give-display-onpage .give-tributes-dedicate-donation legend {
  font-weight: 400;
  text-decoration: underline;
  letter-spacing: 0;
  font-size: 16px;
  border: none;
  padding-bottom: 0;
  margin-bottom: 6px;
}
.give-display-onpage .give-tributes-dedicate-donation legend:hover {
  cursor: pointer;
}
.give-display-onpage .give-tributes-dedicate-donation>div,
.give-display-onpage .give-tributes-show-wrap {
  display: none;
}
.give-display-onpage .give-tributes-label {
  padding: 0;
  margin: 0 !important;
}

.give-display-onpage .give_tributes_type_wrap,
.give-display-onpage .give_tributes_info_wrap {
  padding: 10px;
  background-color: #f3f2f2;
}
.give-display-onpage .give_tributes_info_wrap {
  margin-bottom: 10px;
}
.give-display-onpage .give_tributes_type_wrap {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}
.give-display-onpage .give_tributes_info_wrap  {
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
  padding-top: 0;
  padding-bottom: 0;
}
.give-display-onpage .give-tribute-radio-ul, 
.give-display-onpage .give-tribute-radio-ul li {
  margin-bottom: 0;
}
.give-display-onpage .give-tributes-type-radio {
  padding: 0;
}
.give-display-onpage .give-section-break.give-tributes-legend {
  /* margin: 0 !important; */
}
.give-display-onpage .donation-amount-period {
    height: 40px;
    /* background-image: linear-gradient(180deg, #eaeaea, transparent); */
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
.give-display-onpage .donation-amount-period p {
    font-size: 16px;
    line-height: 18px;
    margin: 0 !important;
}

.give-display-onpage #give_purchase_submit {
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
}
.give-display-onpage .give-submit-button-wrap {
  order: 55;
  margin-top: 10px;
}
.give-display-onpage #give-final-total-wrap {
  order: 10;
  margin-bottom: 6px !important;
  display: flex;
  justify-content: center;
  align-items: center; 
}
.give-display-onpage .give-donation-total-label {
  /* float: none; */

}
.give-display-onpage .give-final-total-amount {
  color: #348ac5;
    font-weight: 600;
    font-size: 24px !important;
}
.give-display-onpage .fee-break-down-message {
  order: 15;
  text-align: center;
  margin-bottom: 0 !important;
}
.give-display-onpage .give-btn.give-submit {
  background-color: #348ac5;
    background-image: none;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
    padding: 20px 10px;
    margin-bottom: 6px;
}
.give-display-onpage div[id*="give-card-name-wrap"] input {
  font-family: Arial, Helvetica, sans-serif;
  color: #32325D;
    font-weight: 500;
    font-size: 16px;
    -webkit-font-smoothing: antialiased;

    background-color: transparent;
    border: none;
    display: block;
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    width: 100%;
    line-height: 20.2px;

}
.give-display-onpage div[id*="give-card-name-wrap"] input::placeholder {
  color: #000;
}

/* MULTI PAGE FORM */
.give-viewing-form-in-iframe .donation-type-selector {
  margin: 0 30px 0 !important;
}
.give-viewing-form-in-iframe .choose-amount .give-donation-levels-wrap .give-donation-level-btn {
    padding: 14px;
    box-shadow:  0 1px 5px 1px rgb(0 0 0 / 36%);
}
.give-viewing-form-in-iframe .choose-amount .give-donation-levels-wrap .give-donation-level-btn.give-btn-level-custom {
    padding: 4px;
}
.give-viewing-form-in-iframe .choose-amount .give-donation-levels-wrap .give-donation-level-btn.give-default-level {
    box-shadow: none;
}
.give-viewing-form-in-iframe [id*=give-form] .give-recurring-donors-choice {
    display: none;
}
.give-viewing-form-in-iframe .choose-amount .give-donation-levels-wrap {
    order: 5;
    margin-top: 10px !important;
}
.give-viewing-form-in-iframe .give-fee-recovery-donors-choice.give-fee-message.form-row {
    order: 8
}
.give-viewing-form-in-iframe .choose-amount .give-total-wrap {
    order: 10;
}
.give-viewing-form-in-iframe .donation-amount-period {
    order: 15;
    height: 20px;
    background: none;
}
.give-viewing-form-in-iframe .donation-amount-period p {
    font-size: 16px;
    line-height: 18px;
    margin: 0 !important;
}
.give-viewing-form-in-iframe form[id*=give-form] .give-donation-amount {
    padding: 10px 20px;
    margin-top: 10px !important;
}
.give-viewing-form-in-iframe .advance-btn, .give-viewing-form-in-iframe .download-btn, .give-viewing-form-in-iframe .give-submit {
    margin-top: 15px;
    box-shadow:  0 1px 5px 1px rgb(0 0 0 / 36%);
}

/* Heart animation */
svg {
  cursor: pointer;
  overflow: visible;
  width: 40px;
  margin: 0;
}

svg #heart {
  transform-origin: center;
  animation: animateHeartOut .3s linear forwards;
}

svg #main-circ {
  transform-origin: 29.5px 29.5px;
}

.heart-checkbox {
  display: none;
}
.heart-checkbox-label {
    padding: 0!important;
    margin: 0;
    width: 30px !important;
    line-height: 30px!important;
    z-index: 9999 !important;
    position: absolute;
    top: 5px;
    left: 20px;
}
input[type=checkbox].heart-checkbox+label:before,
input[type=checkbox].heart-checkbox+label:after {
    content: none;
}
.heart-checkbox:checked+label svg #heart {
  transform: scale(0.2);
  fill: #E2264D;
  animation: animateHeart .3s linear forwards .25s;
}

.heart-checkbox:checked+label svg #main-circ {
  transition: all 2s;
  animation: animateCircle .3s linear forwards;
  opacity: 1;
}

.heart-checkbox:checked+label svg #grp1 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp1 #oval1 {
  transform: scale(0) translate(0, -30px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp1 #oval2 {
  transform: scale(0) translate(10px, -50px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp2 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp2 #oval1 {
  transform: scale(0) translate(30px, -15px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp2 #oval2 {
  transform: scale(0) translate(60px, -15px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp3 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp3 #oval1 {
  transform: scale(0) translate(30px, 0px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp3 #oval2 {
  transform: scale(0) translate(60px, 10px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp4 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp4 #oval1 {
  transform: scale(0) translate(30px, 15px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp4 #oval2 {
  transform: scale(0) translate(40px, 50px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp5 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp5 #oval1 {
  transform: scale(0) translate(-10px, 20px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp5 #oval2 {
  transform: scale(0) translate(-60px, 30px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp6 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp6 #oval1 {
  transform: scale(0) translate(-30px, 0px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp6 #oval2 {
  transform: scale(0) translate(-60px, -5px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp7 {
  opacity: 1;
  transition: .1s all .3s;
}

.heart-checkbox:checked+label svg #grp7 #oval1 {
  transform: scale(0) translate(-30px, -15px);
  transform-origin: 0 0 0;
  transition: .5s transform .3s;
}

.heart-checkbox:checked+label svg #grp7 #oval2 {
  transform: scale(0) translate(-55px, -30px);
  transform-origin: 0 0 0;
  transition: 1.5s transform .3s;
}

.heart-checkbox:checked+label svg #grp2 {
  opacity: 1;
  transition: .1s opacity .3s;
}

.heart-checkbox:checked+label svg #grp3 {
  opacity: 1;
  transition: .1s opacity .3s;
}

.heart-checkbox:checked+label svg #grp4 {
  opacity: 1;
  transition: .1s opacity .3s;
}

.heart-checkbox:checked+label svg #grp5 {
  opacity: 1;
  transition: .1s opacity .3s;
}

.heart-checkbox:checked+label svg #grp6 {
  opacity: 1;
  transition: .1s opacity .3s;
}

.heart-checkbox:checked+label svg #grp7 {
  opacity: 1;
  transition: .1s opacity .3s;
}

@keyframes animateCircle {
  40% {
    transform: scale(10);
    opacity: 1;
    fill: #DD4688;
  }
  55% {
    transform: scale(11);
    opacity: 1;
    fill: #D46ABF;
  }
  65% {
    transform: scale(12);
    opacity: 1;
    fill: #CC8EF5;
  }
  75% {
    transform: scale(13);
    opacity: 1;
    fill: transparent;
    stroke: #CC8EF5;
    stroke-width: .5;
  }
  85% {
    transform: scale(17);
    opacity: 1;
    fill: transparent;
    stroke: #CC8EF5;
    stroke-width: .2;
  }
  95% {
    transform: scale(18);
    opacity: 1;
    fill: transparent;
    stroke: #CC8EF5;
    stroke-width: .1;
  }
  100% {
    transform: scale(19);
    opacity: 1;
    fill: transparent;
    stroke: #CC8EF5;
    stroke-width: 0;
  }
}

@keyframes animateHeart {
  0% {
    transform: scale(0.2);
  }
  40% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}

@keyframes animateHeartOut {
  0% {
    transform: scale(1.4);
  }
  100% {
    transform: scale(1);
  }
}
</style>
<h2 class="hf-give-form-title"><?php echo get_the_title( $form['id'] ); ?></h2>

<div class="donation-type-selector">
        <button class="donation-type one-time selected" aria-current="true" aria-disabled="true">
            <div class="button-gradient"></div><div class="button-text">One Time</div></button>
        <button class="donation-type recurring" aria-current="false" aria-disabled="false"><div class="button-gradient"></div>
        <input type="checkbox" class="heart-checkbox" id="heart-checkbox" />
<label class="heart-checkbox-label" for="heart-checkbox">
      <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg">
        <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)">
          <path d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z" id="heart" fill="#AAB8C2"/>
          <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/>

          <g id="grp7" opacity="0" transform="translate(7 6)">
            <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/>
            <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/>
          </g>

          <g id="grp6" opacity="0" transform="translate(0 28)">
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/>
          </g>

          <g id="grp3" opacity="0" transform="translate(52 28)">
            <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/>
            <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/>
          </g>

          <g id="grp2" opacity="0" transform="translate(44 6)">
            <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/>
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp5" opacity="0" transform="translate(14 50)">
            <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp4" opacity="0" transform="translate(35 50)">
            <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp1" opacity="0" transform="translate(24)">
            <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/>
            <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/>
          </g>
        </g>
      </svg>
    </label>
    <div class="button-text">Monthly</div></button>
        </div>

        <div class="donation-amount-period">
        <p class="one-time">You are making a one-time donation.</p>
        <p class="recurring" style="display:none;">You are making this donation each month.</p>
        </div>

        
       <?php
    }



   
} // end class

$hf_givewp_donations = new HarmonyFundGiveWPDonations();