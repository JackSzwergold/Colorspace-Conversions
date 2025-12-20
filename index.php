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
 * w: https://www.szwergold.com
 * e: jackszwergold@icloud.com
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
require_once('settings/conf.php');
require_once(BASE_FILEPATH . '/common/functions.inc.php');
require_once(BASE_FILEPATH . '/lib/Colorspace.class.php');
require_once(BASE_FILEPATH . '/lib/requestFiltering.class.php');

//**************************************************************************************//
// Manage the request filering stuff.
$requestFilteringClass = new requestFiltering();
$params = $requestFilteringClass->process_parameters();

$url_parts = $requestFilteringClass->process_url_parts($params);
$controller = $requestFilteringClass->process_controllers($url_parts);

//**************************************************************************************//
// Now deal with the colorspace helper class related stuff.
$Colorspace = new Colorspace($DEBUG_MODE);
list($infobox, $infobox_hex) = $Colorspace->manageColorRequest();
$infobox_css = $Colorspace->calculateTextCSS();
$rgb_grid = $Colorspace->rgb_grid();
$pms_grid = $Colorspace->pms_grid();

/******************************************************************************/
// Handle the substitution map stuff.
$substitution_map = array();
$substitution_map['[[BASE_URL]]'] = BASE_URL;
$substitution_map['[[BASE_URI]]'] = BASE_URI;
$substitution_map['[[NONCE]]'] = $NONCE;
$substitution_map['[[YEAR]]'] = date('Y');
$substitution_map['[[INFOBOX]]'] = $infobox;
$substitution_map['[[INFOBOX_CSS]]'] = $infobox_css;
$substitution_map['[[INFOBOX_HEX]]'] = $infobox_hex;
$substitution_map['[[RGB_GRID]]'] = $rgb_grid;
$substitution_map['[[PMS_GRID]]'] = $pms_grid;

/******************************************************************************/
// Load the full page HTML template.
$full_page_html = file_get_contents(BASE_FILEPATH . '/html_includes/' . $TEMPLATE_FRAMEWORK . '/page_body_template.html');

/******************************************************************************/
// Build the final content based on the content and the substitution map.
$full_page_html = strtr($full_page_html, $substitution_map);

//**************************************************************************************//
// Init and display the final content.
$content_type = 'text/html';
$charset = 'utf-8';
header(sprintf('Content-Type: %s; charset=%s', $content_type, $charset));
echo $full_page_html;
exit();

?>
