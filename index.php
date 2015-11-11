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
 * Created: 2015-04-29, js
 * Version: 2015-04-29, js: creation
 *          2015-04-29, js: development & cleanup
 *          2015-04-30, js: development & cleanup
 *          2015-05-03, js: setting more configuration and structure stuff
 *          2015-05-10, js: adding DIV wrapper class & id
 *          2015-05-11, js: setting dynamic DIV wrapper creation
 *
 */

//**************************************************************************************//
// Require the basic classes & functions.

require_once 'conf/conf.inc.php';
require_once BASE_FILEPATH . '/common/functions.inc.php';
require_once BASE_FILEPATH . '/lib/frontendDisplay.class.php';
require_once BASE_FILEPATH . '/lib/frontendDisplayHelpers.php';

//**************************************************************************************//
// Set the page base.
if (FALSE && !empty($controller)) {
  $page_base = BASE_URL . $controller . '/';
}
else {
  $page_base = BASE_URL;
}

//**************************************************************************************//
// Init the "frontendDisplay()" class.

$frontendDisplayClass = new frontendDisplay('text/html', 'utf-8', FALSE, FALSE);
$frontendDisplayClass->setViewMode($mode);
$frontendDisplayClass->setPageTitle($page_title);
$frontendDisplayClass->setPageURL($SITE_URL);
$frontendDisplayClass->setPageCopyright($SITE_COPYRIGHT);
$frontendDisplayClass->setPageDescription($SITE_DESCRIPTION);
$frontendDisplayClass->setPageContent($body);
$frontendDisplayClass->setPageDivs($PAGE_DIVS_ARRAY);
$frontendDisplayClass->setPageDivWrapper('PixelBoxWrapper');
$frontendDisplayClass->setPageRobots($SITE_ROBOTS);
// $frontendDisplayClass->setJavascripts($JAVASCRIPTS_ARRAY);
$frontendDisplayClass->setPageBase($page_base);
// $frontendDisplayClass->setPageURLParts($markdown_parts);
// $frontendDisplayClass->setAmazonInfo($AMAZON_INFO);
// $frontendDisplayClass->setPayPalInfo($PAYPAL_INFO);
$frontendDisplayClass->initContent();

?>