<?php
/**
 * Plugin Name: Geek Goddess InfuCaptcha
 * Plugin URI: https://www.geekgoddess.com/recaptcha-for-infusionsoft-wordpress-plugin
 * Description: Adds a Google reCaptcha v2 to Infusionsoft web forms
 * Version: 1.0.2
 * Author: Jaime Lerner - the Geek Goddess
 * Author URI: https://www.geekgoddess.com
 * License: GPL2
 */

$googlePublic = get_option('google_site_key');
$googleSecret = get_option('google_site_secret');
$googleLang = get_option('google_lang');
$googleTheme = get_option('google_theme');
$customErrorMessage = get_option('custom_error_message');

add_action( 'wp_enqueue_scripts', 'gg_register_jscript' );

function gg_register_jscript() {
  global $googleLang, $googlePublic, $googleTheme;
  $lang = (empty($googleLang)) ? "" : "?hl=$googleLang";
  wp_register_script("google-recaptcha", "https://www.google.com/recaptcha/api.js"."$lang", array(), '', true);
	wp_register_script( 'gg-submit', plugins_url( 'ggsubmit.js' , __FILE__ ), array('jquery'), '1.0.1', true );
  wp_localize_script( 'gg-submit', 'ggAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'googleLang' => "$googleLang", 'googlePublic' => "$googlePublic" , 'googleTheme' => "$googleTheme") );
}

add_action('admin_menu', 'gg_infucaptcha_plugin_menu');

function gg_infucaptcha_plugin_menu() { 
	add_options_page('GG InfuCaptcha Settings', 'GG InfuCaptcha', 'administrator', 'gg-infucaptcha-plugin-settings', 'gg_infucaptcha_plugin_settings_page', ''.plugins_url('recaptcha.png', __FILE__).'');
}

// Add settings link on plugin page
function gg_infucaptcha_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=gg-infucaptcha-plugin-settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'gg_infucaptcha_settings_link' );

function gg_infucaptcha_plugin_settings_page() {
  global $customErrorMessage, $googlePublic, $googleSecret, $googleLang, $googleTheme;
  ?>
  <div class="wrap">
  <h2><?php _e("GG InfuCaptcha for Infusionsoft&reg; Web Forms","gg-infucaptcha-plugin-settings"); ?></h2>
  <?php if( isset($_GET['settings-updated']) ) { ?>
      <div id="message" class="updated">
          <p><strong><?php _e('Settings saved.') ?></strong></p>
      </div>
  <?php } ?>
  <form method="post" action="options.php">
  <?php settings_fields('gg-plugin-settings-group'); ?>
  <?php do_settings_sections('gg-plugin-settings-group'); ?>
  <table class="form-table">
     <tr valign="top">
      <th scope="row" colspan="2"><?php _e("<strong>The first two options are REQUIRED for the recaptcha to validate.</strong>","gg-infucaptcha-plugin-settings");?> 
      <?php _e("<a href='https://www.google.com/recaptcha' target='_blank'>Get your keys here</a>.","gg-infucaptcha-plugin-settings");?></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e("Your Google Site Key<br />(required)","gg-infucaptcha-plugin-settings");?></th>
      <td><input type="text" name="google_site_key" style="min-width:370px" value="<?php echo $googlePublic; ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e("Your Google Secret Key<br />(required)","gg-infucaptcha-plugin-settings");?></th>
      <td><input type="text" name="google_site_secret" style="min-width:370px" value="<?php echo $googleSecret ?>" /></td>
    </tr>
    </div>
     <tr valign="top">
      <th scope="row"><?php _e("Theme Color (defaults to light)","gg-infucaptcha-plugin-settings");?></th>
      <td><input type="radio" name="google_theme" value="light" <?php if($googleTheme=="light" || $googleTheme==""){ echo " checked=\"checked\""; } ?> /> Light <input type="radio" name="google_theme" value="dark" <?php if($googleTheme=="dark"){ echo " checked=\"checked\""; } ?> style="margin-left:10px" /> Dark</td>
    </tr>
   <tr valign="top">
      <th scope="row"><?php _e("Language (defaults to auto-detect - <a href=\"https://developers.google.com/recaptcha/docs/language\" target=\"_blank\">see full listing here</a>)","gg-infucaptcha-plugin-settings");?></th>
      <td><input type="text" name="google_lang" value="<?php echo $googleLang; ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e("Custom Error Message (defaults to 'Please fill out the captcha' - html OK)","gg-infucaptcha-plugin-settings");?></th>
      <td><input type="text" name="custom_error_message" style="min-width:370px" value='<?php echo esc_html(_e($customErrorMessage,"gg-infucaptcha-plugin-settings")); ?>' /></td>
    </tr>
  </table>
  <?php submit_button(); ?>
  </form>
  </div>
 <?php 
}

add_action( 'admin_init', 'gg_infucaptcha_plugin_settings' );

function gg_infucaptcha_plugin_settings() {
	register_setting( 'gg-plugin-settings-group', 'custom_error_message' );
	register_setting( 'gg-plugin-settings-group', 'google_site_key' );
	register_setting( 'gg-plugin-settings-group', 'google_site_secret' );
	register_setting( 'gg-plugin-settings-group', 'google_lang' );
	register_setting( 'gg-plugin-settings-group', 'google_theme' );
}

function gg_infucaptcha_processor(){
  global $googleSecret, $googleLang, $customErrorMessage;
  $infinputtext=$_POST["inf_inputText"];
  $infbuttontext=$_POST["inf_buttonText"];
  require_once "recaptchalib.php";
  $secret = $googleSecret;
  $lang = (empty($googleLang)) ? "en" : $googleLang;
  $theme = (empty($googleTheme)) ? "light" : $googleTheme;
  $resp = null;
  $reCaptcha = new ReCaptcha($secret);
  if ($_POST["g-recaptcha-response"]) {
    $resp = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],$_POST["g-recaptcha-response"]);
    wp_send_json($resp);
  } else {
    $data=$_POST;
    $data['success']=false;
    $data['errorMsg']=(empty($customErrorMessage)) ? "Please fill out the captcha" : $customErrorMessage;
    wp_send_json($data);
  }
}
add_action("wp_ajax_nopriv_gg_infucaptcha_results", "gg_infucaptcha_processor");
add_action("wp_ajax_gg_infucaptcha_results", "gg_infucaptcha_processor");

// SHORTCODE

function gg_addjs(){ 
  wp_enqueue_script( 'google-recaptcha' );
  wp_enqueue_script( 'gg-submit' );
}

add_shortcode( 'infucaptcha', 'gg_addjs' );
