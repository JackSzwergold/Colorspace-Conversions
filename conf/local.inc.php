<?php

/**
 * Local Config File (local.inc.php) (c) by Jack Szwergold
 *
 * Local Config File is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>. 
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2014-02-16, js
 * Version: 2014-02-16, js: creation
 *          2014-02-16, js: development & cleanup
 *
 */

/**************************************************************************************************/
// Define localized defaults.

// Enable or disable JSON debugging output.
$DEBUG_OUTPUT_JSON = false;

// Set the base URL path.
if ($_SERVER['SERVER_NAME'] == 'localhost') {
  define('BASE_PATH', '/Colorspace-Conversions/');
}
else {
  define('BASE_PATH', '/projects/colorspace/');
}

// Site descriptive info.
$SITE_TITLE = 'Colorspace Conversions';
$SITE_DESCRIPTION = 'Some PHP classes to handle colorspace conversions.';
$SITE_URL = 'http://www.preworn.com/projects/colorspace/';
$SITE_COPYRIGHT = '(c) Copyright ' . date('Y') . ' Jack Szwergold. Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.';
$SITE_LICENSE_CODE = 'CC-BY-NC-SA-4.0';
$SITE_LICENSE = 'This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License (CC-BY-NC-SA-4.0)';
$SITE_ROBOTS = 'noindex, nofollow';
// $SITE_VIEWPORT = 'width=device-width, initial-scale=0.65, maximum-scale=2, minimum-scale=0.65, user-scalable=yes';
$SITE_VIEWPORT = 'width=device-width, initial-scale=1.0';
$SITE_IMAGE = 'favicons/icon_200x200.png';
$SITE_FB_ADMINS = '504768652';
$SITE_KEYWORD = 'colorspace';

// Favicon info.
$FAVICONS = array();
$FAVICONS['standard']['rel'] = 'icon';
$FAVICONS['standard']['type'] = 'image/png';
$FAVICONS['standard']['href'] = 'favicons/favicon.ico';
$FAVICONS['opera']['rel'] = 'icon';
$FAVICONS['opera']['type'] = 'image/png';
$FAVICONS['opera']['href'] = 'favicons/speeddial-160px.png';
$FAVICONS['iphone']['rel'] = 'apple-touch-icon-precomposed';
$FAVICONS['iphone']['href'] = 'favicons/apple-touch-icon-57x57-precomposed.png';
$FAVICONS['iphone4_retina']['rel'] = 'apple-touch-icon-precomposed';
$FAVICONS['iphone4_retina']['sizes'] = '114x114';
$FAVICONS['iphone4_retina']['href'] = 'favicons/apple-touch-icon-114x114-precomposed.png';
$FAVICONS['ipad']['rel'] = 'apple-touch-icon-precomposed';
$FAVICONS['ipad']['sizes'] = '72x72';
$FAVICONS['ipad']['href'] = 'favicons/apple-touch-icon-72x72-precomposed.png';
$FAVICONS['57x57']['rel'] = 'apple-touch-icon';
$FAVICONS['57x57']['sizes'] = '57x57';
$FAVICONS['57x57']['href'] = 'favicons/apple-icon-57x57.png';
$FAVICONS['60x60']['rel'] = 'apple-touch-icon';
$FAVICONS['60x60']['sizes'] = '60x60';
$FAVICONS['60x60']['href'] = 'favicons/apple-icon-60x60.png';
$FAVICONS['72x72']['rel'] = 'apple-touch-icon';
$FAVICONS['72x72']['sizes'] = '72x72';
$FAVICONS['72x72']['href'] = 'favicons/apple-icon-72x72.png';
$FAVICONS['76x76']['rel'] = 'apple-touch-icon';
$FAVICONS['76x76']['sizes'] = '76x76';
$FAVICONS['76x76']['href'] = 'favicons/apple-icon-76x76.png';
$FAVICONS['114x114']['rel'] = 'apple-touch-icon';
$FAVICONS['114x114']['sizes'] = '114x114';
$FAVICONS['114x114']['href'] = 'favicons/apple-icon-114x114.png';
$FAVICONS['120x120']['rel'] = 'apple-touch-icon';
$FAVICONS['120x120']['sizes'] = '120x120';
$FAVICONS['120x120']['href'] = 'favicons/apple-icon-120x120.png';
$FAVICONS['144x144']['rel'] = 'apple-touch-icon';
$FAVICONS['144x144']['sizes'] = '144x144';
$FAVICONS['144x144']['href'] = 'favicons/apple-icon-144x144.png';
$FAVICONS['152x152']['rel'] = 'apple-touch-icon';
$FAVICONS['152x152']['sizes'] = '152x152';
$FAVICONS['152x152']['href'] = 'favicons/apple-icon-152x152.png';
$FAVICONS['192x192']['rel'] = 'icon';
$FAVICONS['192x192']['sizes'] = '192x192';
$FAVICONS['192x192']['href'] = 'favicons/android-icon-192x192.png';
$FAVICONS['16x16']['rel'] = 'icon';
$FAVICONS['16x16']['sizes'] = '96x96';
$FAVICONS['16x16']['href'] = 'favicons/favicon-16x16.png';
$FAVICONS['32x32']['rel'] = 'icon';
$FAVICONS['32x32']['sizes'] = '32x32';
$FAVICONS['32x32']['href'] = 'favicons/favicon-32x32.png';
$FAVICONS['96x96']['rel'] = 'icon';
$FAVICONS['96x96']['sizes'] = '96x96';
$FAVICONS['96x96']['href'] = 'favicons/favicon-96x96.png';

// Payment info.
$PAYMENT_INFO = array();
// $PAYMENT_INFO['amazon']['short_name'] = 'Amazon';
// $PAYMENT_INFO['amazon']['emoji'] = '🎥📚📀';
// $PAYMENT_INFO['amazon']['url'] = 'http://www.amazon.com/?tag=preworn-20';
// $PAYMENT_INFO['amazon']['description'] = 'Support me when you buy things on Amazon with this link.';
// $PAYMENT_INFO['paypal']['short_name'] = 'PayPal';
// $PAYMENT_INFO['paypal']['emoji'] = '💰💸💳';
// $PAYMENT_INFO['paypal']['url'] = 'https://www.paypal.me/JackSzwergold';
// $PAYMENT_INFO['paypal']['description'] = 'Support me with a PayPal donation.';

// Social media info.
$SOCIAL_MEDIA_INFO = array();
$SOCIAL_MEDIA_INFO['instagram']['short_name'] = 'Instagram';
$SOCIAL_MEDIA_INFO['instagram']['emoji'] = '📸';
$SOCIAL_MEDIA_INFO['instagram']['url'] = 'https://www.instagram.com/jackszwergold/';
$SOCIAL_MEDIA_INFO['instagram']['description'] = 'Check me out on Instagram.';
// $SOCIAL_MEDIA_INFO['twitter']['short_name'] = 'Twitter';
// $SOCIAL_MEDIA_INFO['twitter']['emoji'] = '🐦';
// $SOCIAL_MEDIA_INFO['twitter']['url'] = 'https://twitter.com/jackszwergold/';
// $SOCIAL_MEDIA_INFO['twitter']['description'] = 'Check me out on Twitter.';

// Ad info.
$AMAZON_RECOMMENDATION = '<script type="text/javascript">
amzn_assoc_placement = "adunit0";
amzn_assoc_enable_interest_ads = "true";
amzn_assoc_tracking_id = "lastplacechamp-20";
amzn_assoc_ad_mode = "auto";
amzn_assoc_ad_type = "smart";
amzn_assoc_marketplace = "amazon";
amzn_assoc_region = "US";
amzn_assoc_linkid = "644fab0801bb9583d5556ffa6d30631f";
amzn_assoc_fallback_mode = {"type":"search","value":"%s"};
amzn_assoc_default_category = "All";
</script>
<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US"></script>';

// Set the page DIVs array.
$PAGE_DIVS_ARRAY = array();
$PAGE_DIVS_ARRAY[] = 'Wrapper';
$PAGE_DIVS_ARRAY[] = 'Padding';
$PAGE_DIVS_ARRAY[] = 'Content';
$PAGE_DIVS_ARRAY[] = 'Padding';
$PAGE_DIVS_ARRAY[] = 'Section';
$PAGE_DIVS_ARRAY[] = 'Padding';
$PAGE_DIVS_ARRAY[] = 'Middle';
$PAGE_DIVS_ARRAY[] = 'Core';
$PAGE_DIVS_ARRAY[] = 'Padding';

// Set the javascript values.
$JAVASCRIPTS_ITEMS = array();

// Set the link items array.
$LINK_ITEMS = array();
$LINK_ITEMS['style_css']['rel'] = 'stylesheet';
$LINK_ITEMS['style_css']['type'] = 'text/css';
$LINK_ITEMS['style_css']['href'] = 'css/style.css';
// $LINK_ITEMS['colors_css']['rel'] = 'stylesheet';
// $LINK_ITEMS['colors_css']['type'] = 'text/css';
// $LINK_ITEMS['colors_css']['href'] = 'css/colors.css';
$LINK_ITEMS['author']['rel'] = 'author';
$LINK_ITEMS['author']['href'] = 'https://plus.google.com/+JackSzwergold';

// Set the controller and parameter stuff.
$VALID_CONTROLLERS = array('parent', 'colorspace', 'value');
$DISPLAY_CONTROLLERS = array('parent', 'colorspace', 'value');
$VALID_GET_PARAMETERS = array('_debug', 'json', 'offset', 'count', 'colorspace', 'value', 'parent', 'child', 'grandchild', 'greatgrandchild');

?>
