<?php

/**
 * Frontend Display Helpers (frontendDisplayHelpers.php) (c) by Jack Szwergold
 *
 * Frontend Display Helpers is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2015-11-10, js
 * Version: 2015-11-10, js: creation
 *          2015-11-10, js: development
 *
 */

//**************************************************************************************//
// Require the basics.

require_once BASE_FILEPATH . '/lib/colorspace_conversions.class.php';
require_once BASE_FILEPATH . '/lib/colorspace_helpers.class.php';
require_once BASE_FILEPATH . '/lib/colorspace_display.class.php';

//**************************************************************************************//
// The beginnings of a frontend display helper class.

class frontendDisplayHelper {

  public function init($VIEW_MODE = 'longlist', $page_base, $page_base_suffix = '', $DEBUG_MODE = FALSE) {
   global $VALID_GET_PARAMETERS;

	//**************************************************************************************//
	// Set the mode.

	$VIEW_MODE = 'large';

	//**************************************************************************************//
	// Get the URL param & set the markdown file as well as the page title.

	// Init the arrays.
	$url_parts = array();
	$markdown_parts = array();
	$title_parts = array($SITE_TITLE);

	// Parse the '$_GET' parameters.
	foreach($VALID_GET_PARAMETERS as $get_parameter) {
	  $$get_parameter = '';
	  if (array_key_exists($get_parameter, $_GET) && !empty($_GET[$get_parameter])) {
		if (in_array($get_parameter, $VALID_GET_PARAMETERS)) {
		  $$get_parameter = $_GET[$get_parameter];
		}
	  }
	}

	// Set the controller.
	if (!empty($colorspace)) {
	  $url_parts[] = $colorspace;
	  $title_parts[] = strtoupper($colorspace);
	}

	// Set the page.
	if (!empty($colorspace) && !empty($value)) {
	  $url_parts[] = $value;
	  $title_parts[] = $value;
	}

	// Set the page title.
	$page_title = join(' / ', $title_parts);
	$page_title = ucwords(preg_replace('/_/', ' ', $page_title));

	//**************************************************************************************//
	// Run the actual function and get the parts.

	list($colorspace, $page_title, $url_parts) = $this->parse_parameters($SITE_TITLE, $VALID_GET_PARAMETERS);

	//**************************************************************************************//
	// Init the display class and get the values.

	$DisplayClass = new Display();
	$DisplayClass->show_rgb_grid = true;
	// $DisplayClass->show_cmyk_grid = true;
	$DisplayClass->show_pms_grid = true;
	$html_content = $DisplayClass->init($colorspace, $value);

    return array($VIEW_MODE, $html_content, null, $page_title, $url_parts);

  } // init


  //**************************************************************************************//
  // Here is the function to parse the parameters.

  function parse_parameters ($SITE_TITLE, $VALID_GET_PARAMETERS) {

    // Init the arrays.
    $url_parts = array();
    $title_parts = array($SITE_TITLE);

    // Parse the '$_GET' parameters.
    foreach($VALID_GET_PARAMETERS as $get_parameter) {
  	$$get_parameter = '';
  	if (array_key_exists($get_parameter, $_GET) && !empty($_GET[$get_parameter])) {
  	  if (in_array($get_parameter, $VALID_GET_PARAMETERS)) {
  		$$get_parameter = $_GET[$get_parameter];
  	  }
  	}
    }

    // Set the controller.
    if (!empty($colorspace)) {
  	$url_parts[] = $colorspace;
  	$title_parts[] = strtoupper($colorspace);
    }

    // Set the page.
    if (!empty($colorspace) && !empty($value)) {
  	$url_parts[] = $value;
  	$title_parts[] = $value;
     }

    // Set the page title.
    $page_title = join(' / ', $title_parts);
    $page_title = preg_replace('/_/', ' ', $page_title);
    // $page_title = ucwords($page_title);

    return array($colorspace, $page_title, $url_parts);

  } // parse_parameters

} // frontendDisplayHelper

?>