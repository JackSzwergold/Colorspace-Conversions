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
require_once BASE_FILEPATH . '/lib/contentCreation.class.php';

//**************************************************************************************//
// Init the "contentCreation()" class.

$contentCreationClass = new contentCreation();
list($params, $page_title, $markdown_file) = $contentCreationClass->init();

//**************************************************************************************//
// Set the page base.

$page_base = BASE_URL;
if (array_key_exists('controller', $params) && !empty($params['controller'])) {
  $page_base = BASE_URL . $params['controller'] . '/';
}

//**************************************************************************************//
// Init the "frontendDisplay()" class.

$frontendDisplayClass = new frontendDisplay(FALSE, FALSE);
if ($params['controller'] == 'json') {
  $frontendDisplayClass->setContentType('application/json');
  $frontendDisplayClass->setPageContentJSON($json_content);
}
else {
  $frontendDisplayClass->setContentType('text/html');
}
$frontendDisplayClass->setCharset('utf-8');
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
$frontendDisplayClass->setFaviconItems($FAVICONS);
$frontendDisplayClass->setPageBase($page_base);
// $frontendDisplayClass->setPageURLParts($markdown_parts);
// $frontendDisplayClass->setPaymentInfo($PAYMENT_INFO);
$frontendDisplayClass->initContent();

?>
