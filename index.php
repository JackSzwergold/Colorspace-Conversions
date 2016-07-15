<?php

/**
 * Index Controller (index.php) (c) by Jack Szwergold
 *
 * Index Controller is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2014-01-20, js
 * Version: 2014-01-20, js: creation
 *          2014-01-20, js: development & cleanup
 *          2014-02-16, js: adding configuration settings
 *          2014-02-16, js: adding controller logic
 *          2014-02-17, js: setting a 'base'
 *          2014-03-02, js: adding a better page URL
 *
 */

//**************************************************************************************//
// Require the basic configuration settings & functions.

require_once 'conf/conf.inc.php';
require_once BASE_FILEPATH . '/common/functions.inc.php';
require_once BASE_FILEPATH . '/lib/frontendDisplay.class.php';
require_once BASE_FILEPATH . '/lib/frontendDisplayHelper.class.php';
require_once BASE_FILEPATH . '/lib/contentCreation.class.php';
require_once BASE_FILEPATH . '/lib/Spyc.php';

//**************************************************************************************//
// Init the "contentCreation()" class.

$contentCreationClass = new contentCreation();
list($params, $page_title, $markdown_file) = $contentCreationClass->init();

//**************************************************************************************//
// Set the debug mode value.

$DEBUG_MODE = array_key_exists('_debug', $params);

//**************************************************************************************//
// Set the JSON mode value.

$JSON_MODE = array_key_exists('json', $params);

//**************************************************************************************//
// Set the page base.

$page_base = BASE_URL;
$controller = $SITE_DEFAULT_CONTROLLER;
$url_parts = array();
$controller_parts = array('parent', 'child', 'grandchild', 'greatgrandchild');
foreach ($controller_parts as $part) {
  if (array_key_exists($part, $params) && !empty($params[$part]) && $params[$part] != 'index') {
    $url_parts[$part] = rawurlencode($params[$part]);
  }
}
if (!empty($url_parts)) {
  $controller = implode($url_parts, '/');
  $page_base = BASE_URL . $controller . '/';
}

//**************************************************************************************//
// Set the query suffix to the page base.

$page_base_suffix = $JSON_MODE ? '?json' : '';

//**************************************************************************************//
// Instantiate the front end display helper class.

$frontendDisplayHelperClass = new frontendDisplayHelper();

//**************************************************************************************//
// Set some values to the front end display helper class.

$frontendDisplayHelperClass->setDefaultController($SITE_DEFAULT_CONTROLLER);
$frontendDisplayHelperClass->setSelectedController($controller);
$frontendDisplayHelperClass->setPageBase($page_base);
$frontendDisplayHelperClass->setPageBaseSuffix($page_base_suffix);

//**************************************************************************************//
// Init the content via the class.

$frontendDisplayHelperClass->initContent($DEBUG_MODE);

//**************************************************************************************//
// Get values from the front end display helper class.

$VIEW_MODE = $frontendDisplayHelperClass->getViewMode();
$page_title = $frontendDisplayHelperClass->getPageTitle();
$url_parts = $frontendDisplayHelperClass->getURLParts();
$html_content = $frontendDisplayHelperClass->getHTMLContent();
$json_content = $frontendDisplayHelperClass->getJSONContent();

//**************************************************************************************//
// Init the front end display class and set other things.

$frontendDisplayClass = new frontendDisplay();
$frontendDisplayClass->setPageJSONContent($json_content);
$frontendDisplayClass->setJSONMode($JSON_MODE);
$frontendDisplayClass->setDebugMode($DEBUG_MODE);
$frontendDisplayClass->setContentType(($JSON_MODE ? 'application/json' : 'text/html'));
$frontendDisplayClass->setCharset('utf-8');
$frontendDisplayClass->setViewMode($VIEW_MODE);
$frontendDisplayClass->setPageTitle($SITE_TITLE . $page_title);
$frontendDisplayClass->setPageURL($SITE_URL . join('/', $url_parts));
$frontendDisplayClass->setPageCopyright($SITE_COPYRIGHT);
$frontendDisplayClass->setPageLicense($SITE_LICENSE);
$frontendDisplayClass->setPageDescription($SITE_DESCRIPTION);
$frontendDisplayClass->setPageContent($html_content);
$frontendDisplayClass->setPageDivs($PAGE_DIVS_ARRAY);
$frontendDisplayClass->setPageDivWrapper('PixelBoxWrapper');
$frontendDisplayClass->setPageViewport($SITE_VIEWPORT);
$frontendDisplayClass->setPageRobots($SITE_ROBOTS);
// $frontendDisplayClass->setJavaScriptItems($JAVASCRIPTS_ITEMS);
$frontendDisplayClass->setLinkItems($LINK_ITEMS);
$frontendDisplayClass->setFaviconItems($FAVICONS);
$frontendDisplayClass->setPageBase($page_base . $page_base_suffix);
$frontendDisplayClass->setPageURLParts($params);
// $frontendDisplayClass->setPaymentInfo($PAYMENT_INFO);
$frontendDisplayClass->setSocialMediaInfo($SOCIAL_MEDIA_INFO);
$frontendDisplayClass->setAdBanner($AMAZON_RECOMMENDATION);

//**************************************************************************************//
// Init the core content and set the header and footer items..

// Set the core content.
$frontendDisplayClass->initCoreContent();

// Set the header.
// $navigation = $frontendDisplayClass->setNavigation();
// $frontendDisplayClass->setBodyHeader($navigation);

// Set the footer.
// $ad_banner = $frontendDisplayClass->setAdBannerFinal();
// $frontendDisplayClass->setBodyFooter($ad_banner);

//**************************************************************************************//
// Init and display the final content.

$frontendDisplayClass->initHTMLContent();

?>
