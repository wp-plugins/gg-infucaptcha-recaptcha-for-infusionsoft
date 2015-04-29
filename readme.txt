=== GG Infucaptcha reCaptcha for Infusionsoft ===
Contributors: Geek Goddess
Tags: infusionsoft, captcha, recaptcha, antispam
Requires at least: 4.0
Tested up to: 4.2.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Inserts Google’s new reCAPTCHA into Infusionsoft web forms

== Description ==

Infusionsoft&reg; has a native captcha you can insert into web forms, but it's not user-friendly, blocks submissions, and is ugly. With the new reCAPTCHA API, Google has released a captcha that the bulk of your users will only have to check a box to pass, optimizing opt-ins while protecting you from inserting spam into your Infusionsoft automation.

As a bonus, activating this plugin in an Infusionsoft web form will also set up basic HTML5 form validation for it.

Requires PHP 5.2+ for json support

== Installation ==

1. Upload the `gg-infucaptcha` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your Google reCAPTCHA keys in the settings
4. Use the [infucaptcha] shortcode

== Frequently Asked Questions ==

= How do I insert the captcha? =

1. Cut and paste the "HTML Code (unstyled)" version of the web form into your WordPress post or page using the "Text" tab (NOT the Visual tab). Do NOT use any other version of the web form. They will not work with this plugin.
2. Place the shortcode [infucaptcha] in your post or page just above where you pasted the web form.

= Can this be used in a widget? =

Yes, anywhere a shortcode is supported, you can insert the captcha.

= Are there any requirements for the form code? =

The plugin works by looking for certain markup in a standard Infusionsoft form. To insert correctly, you must leave all of the Infusionsoft classes intact in the web form code. You are free to add additional classes, but do not remove the ones already in place. In addition, for HTML5 validation to take place, you must have a "label" tag, and the text within that label tag must include an asterisk for a required field. By default, this is how Infusionsoft forms are built, so you would have to do nothing to the form to have it validate.

At a bare minimum, you will need to have the "infusion-submit" class in an element before the submit button (i.e. a div), and the "infusion-form" class applied to the form element.

See the support page at <a href="https://www.geekgoddess.com/recaptcha-for-infusionsoft-wordpress-plugin/">https://www.geekgoddess.com/recaptcha-for-infusionsoft-wordpress-plugin/</a> for more answers.

== Screenshots ==

1. Settings panel
2. Web form on a page with alternate language and dark theme selected as options
3. Web form in a widget
4. Adding shortcode to post

== Changelog ==

= 1.0.1 =

* Updated json function call and added in error checking


= 1.0.0 =

* Initial release

== Upgrade Notice ==

= 1.0.1 =
Possible fix for json response error on some configurations

