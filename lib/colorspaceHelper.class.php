<?php

/**
 * Frontend Display Helper Class (frontendDisplayHelper.class.php) (c) by Jack Szwergold
 *
 * Frontend Display Helper Class is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: https://www.szwergold.com
 * e: jackszwergold@icloud.com
 *
 * Created: 2015-11-10, js
 * Version: 2015-11-10, js: creation
 *          2015-11-10, js: development
 *          2016-07-26, js: refactoring and cleanup
 *
 */

//**************************************************************************************//
// Require the basics.
require_once(BASE_FILEPATH . '/lib/colorspace_conversions.class.php');
require_once(BASE_FILEPATH . '/lib/colorspace_helpers.class.php');
require_once(BASE_FILEPATH . '/lib/colorspace_display.class.php');

//**************************************************************************************//
// The beginnings of a front end display helper class.
class colorspaceHelper {

  public $controller = '';
  public $url_parts = array();

  public $page_title = '';

  public $VIEW_MODE = null;
  public $DEBUG_MODE = FALSE;

  //**************************************************************************************//
  // Filter the view mode.
  private function filterViewMode($mode = null, $mode_options = null) {
    global $SITE_DEFAULT_CONTROLLER;

    //************************************************************************************//
    // Do something.
    if (!empty($mode) && $mode == 'random') {
      $mode_keys = array_keys($mode_options);
      shuffle($mode_keys);
      $mode = $mode_keys[0];
    } // if
    else if (!empty($mode) && !array_key_exists($mode, $mode_options)) {
      $mode = $SITE_DEFAULT_CONTROLLER;
    } // else if

    //**************************************************************************************//
    // Return the final return value.
    return $mode;

  } // filterViewMode

  //**************************************************************************************//
  // Init content.
  public function initContent($DEBUG_MODE = false) {
    global $SITE_TITLE, $VALID_GET_PARAMETERS;

    //************************************************************************************//
    // Set the view mode.
    $this->VIEW_MODE = $this->controller;

    //************************************************************************************//
    // Set the debug mode.
    $this->DEBUG_MODE = $DEBUG_MODE;

    //************************************************************************************//
    // Init the arrays.
    $url_parts = array();
    $markdown_parts = array();
    $title_parts = array($SITE_TITLE);

    //************************************************************************************//
    // Parse the '$_GET' parameters.
    foreach($VALID_GET_PARAMETERS as $get_parameter) {
      $$get_parameter = '';
      if (array_key_exists($get_parameter, $_GET) && !empty($_GET[$get_parameter])) {
        if (in_array($get_parameter, $VALID_GET_PARAMETERS)) {
          $$get_parameter = $_GET[$get_parameter];
        } // if
      } // if
    } // foreach

    //************************************************************************************//
    // Set the controller.
    if (!empty($colorspace)) {
      $url_parts[] = $colorspace;
      $title_parts[] = strtoupper($colorspace);
    } // if

    //************************************************************************************//
    // Set the page.
    if (!empty($colorspace) && !empty($value)) {
      $url_parts[] = $value;
      $title_parts[] = $value;
    } // if

    //************************************************************************************//
    // Set the page title.
    $this->page_title = join(' / ', $title_parts);
    $this->page_title = ucwords(preg_replace('/_/', ' ', $this->page_title));

    //************************************************************************************//
    // Set the URL parts.
    $this->url_parts = $url_parts;

    //**************************************************************************************//
    // Run the actual function and get the parts.
    list($colorspace, $page_title, $url_parts) = $this->parse_parameters();

    //**************************************************************************************//
    // Init the display class and get the values.
    $DisplayClass = new Display();
    $infobox = $DisplayClass->init($colorspace, $value);

    //**************************************************************************************//
    // Set the final return value.
    $ret = $infobox;

    //**************************************************************************************//
    // Return the final return value.
    return $ret;

  } // initContent

  //**************************************************************************************//
  // A function to render RGB grid content.
  public function rgbGridContent() {

    //**************************************************************************************//
    // Do something.
    $ret = $DisplayClass->rgb_grid();

    //**************************************************************************************//
    // Return the final return value.
    return $ret;

  } // rgbGridContent

  //**************************************************************************************//
  // A function to render RGB grid content.
  public function pmsGridContent() {

    //**************************************************************************************//
    // Do something.
    $ret = $DisplayClass->pms_grid();

    //**************************************************************************************//
    // Return the final return value.
    return $ret;

  } // pmsGridContent

  //**************************************************************************************//
  // A function to parse the parameters.
  private function parse_parameters() {
    global $SITE_TITLE, $VALID_GET_PARAMETERS;

    //************************************************************************************//
    // Init the arrays.
    $url_parts = array();
    $title_parts = array($SITE_TITLE);

    //************************************************************************************//
    // Parse the '$_GET' parameters.
    foreach($VALID_GET_PARAMETERS as $get_parameter) {
      $$get_parameter = '';
      if (array_key_exists($get_parameter, $_GET) && !empty($_GET[$get_parameter])) {
        if (in_array($get_parameter, $VALID_GET_PARAMETERS)) {
          $$get_parameter = $_GET[$get_parameter];
        } // if
      } // if
    } // foreach

    //************************************************************************************//
    // Set the controller.
    if (!empty($colorspace)) {
      $url_parts[] = $colorspace;
      $title_parts[] = strtoupper($colorspace);
    } // if

    //************************************************************************************//
    // Set the page.
    if (!empty($colorspace) && !empty($value)) {
      $url_parts[] = $value;
      $title_parts[] = $value;
    } // if

    //************************************************************************************//
    // Set the page title.
    $page_title = join(' / ', $title_parts);
    $page_title = preg_replace('/_/', ' ', $page_title);
    // $page_title = ucwords($page_title);

    return array($colorspace, $page_title, $url_parts);

  } // parse_parameters

} // colorspaceHelper

?>