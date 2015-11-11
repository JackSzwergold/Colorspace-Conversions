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
  define('BASE_PATH', '/art/colorspace/');
}

// Site descriptive info.
$SITE_TITLE = 'Colorspace Conversions';
$SITE_DESCRIPTION = 'Some PHP classes to handle colorspace conversions.';
$SITE_URL = 'http://www.preworn.com/colorspace/';
$SITE_COPYRIGHT = '(c) Copyright ' . date('Y') . ' Jack Szwergold. Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.';
$SITE_ROBOTS = 'noindex, nofollow';
$SITE_VIEWPORT = 'width=device-width, initial-scale=0.65, maximum-scale=2, minimum-scale=0.65, user-scalable=yes';

// Amazon link info.
$AMAZON_INFO = array();
$AMAZON_INFO['short_name'] = 'amazon';
$AMAZON_INFO['url'] = 'http://www.amazon.com/?tag=lastplacechamp-20';
$AMAZON_INFO['description'] = 'Support me when you buy things on Amazon with this link.';

// PayPal link info.
$PAYPAL_INFO = array();
$PAYPAL_INFO['short_name'] = 'paypal';
$PAYPAL_INFO['url'] = 'https://www.paypal.me/JackSzwergold';
$PAYPAL_INFO['description'] = 'Support me with a PayPal donation.';

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
$JAVASCRIPTS_ARRAY = array();

// Set the controller and parameter stuff.
$VALID_CONTROLLERS = array('colorspace', 'value');
$DISPLAY_CONTROLLERS = array('colorspace', 'value');
$VALID_GET_PARAMETERS = array('_debug', 'colorspace', 'value');

?>
