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
require_once BASE_FILEPATH . '/lib/frontendDisplayHelpers.php';

//**************************************************************************************//
// Set the page base.

if (isset($controller) && !empty($controller)) {
  $page_base = BASE_URL . $controller . '/';
}
else {
  $page_base = BASE_URL;
}

//**************************************************************************************//
// Init the "frontendDisplay()" class.

$frontendDisplayClass = new frontendDisplay('text/html', 'utf-8', FALSE, FALSE);
$frontendDisplayClass->setViewMode($VIEW_MODE);
$frontendDisplayClass->setPageTitle($page_title);
$frontendDisplayClass->setPageURL($SITE_URL . join('/', $url_parts));
$frontendDisplayClass->setPageCopyright($SITE_COPYRIGHT);
$frontendDisplayClass->setPageDescription($SITE_DESCRIPTION);
$frontendDisplayClass->setPageContent($body);
$frontendDisplayClass->setPageDivs($PAGE_DIVS_ARRAY);
$frontendDisplayClass->setPageDivWrapper('PixelBoxWrapper');
$frontendDisplayClass->setPageRobots($SITE_ROBOTS);
// $frontendDisplayClass->setJavaScriptItems($JAVASCRIPTS_ITEMS);
$frontendDisplayClass->setCSSItems($CSS_ITEMS);
$frontendDisplayClass->setPageBase($page_base);
// $frontendDisplayClass->setPageURLParts($markdown_parts);
// $frontendDisplayClass->setPaymentInfo($PAYMENT_INFO);
$frontendDisplayClass->initContent();
?>