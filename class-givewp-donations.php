<?php 
/**
 * PHP class to handle modifications to the GiveWP forms for the 
 * Harmony Foundation needs.
 * 
 * Version: 1.5
 * Date: 2020-12-14
 */


namespace HarmonyFund;
use Give\Helpers\Form\Template as FormTemplateUtils;

class HarmonyFundGiveWPDonations {

  
    public function __construct() {
      add_action( 'init', array($this, 'hf_enqueue_scripts'), 100);
      add_action( 'give_embed_head', array($this, 'hf_enqueue_scripts'), 100);
      add_filter( 'give_form_content_output', array($this, 'hf_give_form_content_output'), 10, 3);
      add_action( 'give_before_donation_levels', array($this, 'hf_add_elements_to_givewp_form_amount_section'), 10, 2); 
      add_action( 'give_donation_form_after_personal_info', array($this, 'hf_add_elements_to_givewp_form_payment_section'), 10, 1); 
    } // end function construct

    /**
     * Add the JS and CSS scripts necessary for the forms
     */
    public function hf_enqueue_scripts() {
      wp_enqueue_style( 'hf-givewp', get_stylesheet_directory_uri() . '/harmonyfund-givewp.css', '', '1.5.1a');
      wp_enqueue_script( 'hf-givewp', get_stylesheet_directory_uri() . '/harmonyfund-givewp.js', array( 'jquery' ), '1.5.1' , true);
    }

    /**
     * This is for the legacy donation form and will output a featured image
     * as well as wrap the form in a box.
     */
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
    } // end function

    /**
     * Adds the various HTML elements to the GiveWP forms on the amount tab
     */
    public function hf_add_elements_to_givewp_form_amount_section($output, $form) {
      // Get the GiveWP color settings
      $templateOptions = FormTemplateUtils::getOptions();
      $primary_color        = ! empty( $templateOptions['introduction']['primary_color'] ) ? $templateOptions['introduction']['primary_color'] : '#28C77B';
      $lighter_color = $this->hf_adjust_brightness($primary_color, 25);
        ?>

      <style>
        .donation-type-selector .donation-type.selected .button-gradient {
            background: <?php echo $primary_color; ?>;
            background-image: -webkit-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%);
            background-image: -o-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%);
            background-image: linear-gradient(to bottom,<?php echo $lighter_color; ?> 0,<?php echo $primary_color; ?> 100%);
        }
        .give-viewing-form-in-iframe .choose-amount .give-donation-levels-wrap .give-donation-level-btn.give-default-level {
            background: <?php echo $primary_color; ?> !important;
            background-image: -webkit-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%) !important;
            background-image: -o-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%) !important;
            background-image: linear-gradient(to bottom,<?php echo $lighter_color; ?> 0,<?php echo $primary_color; ?> 100%) !important;
            color: #fff !important;
        }
        .give-viewing-form-in-iframe .hf-advance-btn-wrapper:hover .advance-btn {
          background: <?php echo $lighter_color; ?> !important;
        }
        .give-viewing-form-in-iframe .give-section.payment .give-fee-recovery-donors-choice {
          order: 15;
          background: <?php echo $primary_color; ?> 
        }
        .hf-lightbox-content .hf-donate-keep-onetime:after {
          border-bottom: 2px solid <?php echo $primary_color; ?>;
        }
      </style>
      <h2 class="hf-give-form-title"><?php echo get_the_title( $form['id'] ); ?></h2>

      <div class="donation-type-selector">
        <button class="donation-type one-time selected" aria-current="true" aria-disabled="true">
          <div class="button-gradient"></div>
          <div class="button-text">One Time</div>
        </button>
        <button class="donation-type recurring" aria-current="false" aria-disabled="false">
          <div class="button-gradient"></div>
          <input type="checkbox" class="heart-checkbox" id="heart-checkbox" />
          <label class="heart-checkbox-label" for="heart-checkbox">
            <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg"> <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)"> <path d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z" id="heart" fill="#AAB8C2"/> <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/> <g id="grp7" opacity="0" transform="translate(7 6)"> <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/> <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/> </g> <g id="grp6" opacity="0" transform="translate(0 28)"> <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/> <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/> </g> <g id="grp3" opacity="0" transform="translate(52 28)"> <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/> <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/> </g> <g id="grp2" opacity="0" transform="translate(44 6)"> <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/> <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/> </g> <g id="grp5" opacity="0" transform="translate(14 50)"> <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/> <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/> </g> <g id="grp4" opacity="0" transform="translate(35 50)"> <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/> <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/> </g> <g id="grp1" opacity="0" transform="translate(24)"> <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/> <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/> </g> </g> </svg>
          </label>
          <div class="button-text"><span class="hf-recurring-period-label">Monthly</span></div>
        </button>
      </div>

      <div class="donation-amount-period">
        <p class="one-time">You are making a one-time donation.</p>
        <p class="recurring" style="display:none;">You are making this donation each <span class="hf-recurring-period">month</span>.</p>
      </div>
      
      <?php
    } // end function
    /**
     * Adds the various HTML elements to the GiveWP forms on the payment tab
     */
    public function hf_add_elements_to_givewp_form_payment_section($form) {
      // Get the GiveWP color settings
      // $templateOptions = FormTemplateUtils::getOptions();
      // $primary_color        = ! empty( $templateOptions['introduction']['primary_color'] ) ? $templateOptions['introduction']['primary_color'] : '#28C77B';
      // $lighter_color = $this->hf_adjust_brightness($primary_color, 25);
        ?>

      <div class="hf-popup hf-modal">
        <div class="hf-modal-box">
          <div class="hf-close"><i class="fa fa-times" aria-hidden="true"></i></div><!--hf-close-->
          <div class="hf-lightbox-content">
            <div class="hf-donate-icon"></div>
            <div class="hf-donate-plea">
              <div class="hf-calendar-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 789.3866 835.3172"><defs><style>.cls-1{isolation:isolate;}.cls-2{fill:#2d8996;opacity:0.24;mix-blend-mode:multiply;}.cls-3{fill:#f5f5f5;}.cls-4{fill:url(#linear-gradient);}.cls-5{fill:none;stroke:#fadda3;stroke-linecap:round;stroke-miterlimit:10;stroke-width:18.9284px;opacity:0.38;}.cls-6{fill:#47404f;}.cls-7{fill:#d8cce8;}.cls-8{fill:#ff4f70;}</style><linearGradient id="linear-gradient" x1="222.4438" y1="104.7042" x2="960.9248" y2="104.7042" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#eda03c"/><stop offset="1" stop-color="#f5211b"/></linearGradient></defs><g class="cls-1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_3" data-name="Layer 3"><path class="cls-2" d="M50.9056,107.9961h738.481a0,0,0,0,1,0,0V811.1986a24.1186,24.1186,0,0,1-24.1186,24.1186H75.0242a24.1186,24.1186,0,0,1-24.1186-24.1186V107.9961A0,0,0,0,1,50.9056,107.9961Z"/><path class="cls-3" d="M0,38.371H738.481a0,0,0,0,1,0,0V751.422a25.43,25.43,0,0,1-25.43,25.43H25.43A25.43,25.43,0,0,1,0,751.4221V38.371a0,0,0,0,1,0,0Z"/><rect class="cls-4" y="38.371" width="738.481" height="132.6665"/><line class="cls-5" x1="242.9622" y1="107.9961" x2="495.5188" y2="107.9961"/><path class="cls-6" d="M131.1014,0h3.4829A17.1737,17.1737,0,0,1,151.758,17.1737V87.3891a17.1737,17.1737,0,0,1-17.1737,17.1737h-3.483a17.1737,17.1737,0,0,1-17.1737-17.1737V17.1738A17.1738,17.1738,0,0,1,131.1014,0Z"/><rect class="cls-6" x="586.7229" width="37.8305" height="104.5628" rx="17.1738"/><rect class="cls-7" x="241.9717" y="279.8541" width="92.5364" height="92.5364"/><rect class="cls-7" x="395.2241" y="279.8541" width="92.5364" height="92.5364"/><rect class="cls-7" x="548.4764" y="279.8541" width="92.5365" height="92.5364"/><rect class="cls-7" x="88.7192" y="419.8085" width="92.5365" height="92.5364"/><rect class="cls-7" x="241.9717" y="419.8085" width="92.5364" height="92.5364"/><rect class="cls-7" x="395.2241" y="419.8085" width="92.5364" height="92.5364"/><rect class="cls-7" x="548.4764" y="419.8085" width="92.5365" height="92.5364"/><rect class="cls-7" x="88.7192" y="559.7628" width="92.5365" height="92.5365"/><rect class="cls-7" x="241.9717" y="559.7628" width="92.5364" height="92.5365"/><rect class="cls-8" x="395.2241" y="559.7628" width="92.5364" height="92.5365"/></g></g></g></svg></div>
              <p>Would you like to change your donation to a smaller but regular donation?</p>

              <p><strong>Ongoing and regular donations help us more as we focus on our mission.</strong></p>

              <p><button class="hf-donate-modify give-btn">Donate <span class="hf-donate-new-amount"></span>/<span class="hf-recurring-period">month</span></button></p>

              <p><a class="hf-donate-keep-onetime">Keep my one-time gift of <span class="hf-donate-original-amount"></span></a></p>
            </div>
          </div>
        </div><!--hf-modal-box-->
        <div class="hf-modal-background"></div><!--hf-modal-background-->
      </div><!-- hf-modal -->

    <?php
    }
    /**
     * Computes new hex color values given a starting value and then the 
     * increase or decrease in steps.
     */
    private function hf_adjust_brightness($hex, $steps) {
      // Steps should be between -255 and 255. Negative = darker, positive = lighter
      $steps = max(-255, min(255, $steps));

      // Normalize into a six character long hex string
      $hex = str_replace('#', '', $hex);
      if (strlen($hex) == 3) {
          $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
      }

      // Split into three parts: R, G and B
      $color_parts = str_split($hex, 2);
      $return = '#';

      foreach ($color_parts as $color) {
          $color   = hexdec($color); // Convert to decimal
          $color   = max(0,min(255,$color + $steps)); // Adjust color
          $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
      }

      return $return;
    } // end function

} // end class

$hf_givewp_donations = new HarmonyFundGiveWPDonations();