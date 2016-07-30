<?php

/**
 * Request Filtering (requestFiltering.class.php) (c) by Jack Szwergold
 *
 * Request Filtering is licensed under a
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
// The beginnings of a content creation class.

class requestFiltering {

  //**************************************************************************************//
  // Process the 'GET' parameters parameters.
  function process_parameters () {
    global $VALID_GET_PARAMETERS;

    // Roll through the GET parameters and validate them.
    $params = array();
    foreach($VALID_GET_PARAMETERS as $key => $value) {
      if (array_key_exists($value, $_GET)) {
        if (in_array($value, $VALID_GET_PARAMETERS)) {
          if ($value == 'parent') {
            $params[$value] = preg_replace('/[^A-Za-z-_]/s', '', trim($_GET[$value]));
          }
          else if ($value == '_debug') {
            $params[$value] = TRUE;
          }
          else if ($value == 'json') {
            $params[$value] = TRUE;
          }
          else if ($value == 'offset') {
            $params[$value] = intval($_GET[$value]);
          }
          else if ($value == 'count') {
            $params[$value] = intval($_GET[$value]);
          }
          else {
            $params[$value] = trim($_GET[$value]);
          }
        }
      }
    }

    return $params;

  } // process_parameters

  //**************************************************************************************//
  // Process the debug mode.
  function process_debug_mode ($params = array()) {

    return array_key_exists('_debug', $params);

  } // process_debug_mode

  //**************************************************************************************//
  // Process the JSON mode.
  function process_json_mode ($params = array()) {

    return array_key_exists('json', $params);

  } // process_json_mode

  //**************************************************************************************//
  // Process the query string append.
  function process_query_string_append ($modes = array()) {

    $ret = array();
    foreach ($modes as $mode_key => $mode_value) {
      if ($mode_key && $mode_value) {
        $ret[$mode_key] = TRUE;
      }
    }

    return !empty($ret) ? '?' . implode('&', array_keys($ret)) : null;

  } // process_query_string_append

  //**************************************************************************************//
  // Process the URL parts.
  function process_url_parts ($params = array()) {
    global $VALID_CONTROLLERS;

    $url_parts = array();
	foreach ($VALID_CONTROLLERS as $controller) {
	  if (array_key_exists($controller, $params) && !empty($params[$controller]) && $params[$controller] != 'index') {
		$url_parts[$controller] = rawurlencode($params[$controller]);
	  }
	}

	return $url_parts;

  } // process_url_parts

  //**************************************************************************************//
  // Process the controllers.
  function process_controllers ($url_parts = array()) {
    global $SITE_DEFAULT_CONTROLLER;

    $controller = $SITE_DEFAULT_CONTROLLER;

    if (!empty($url_parts)) {
	  if (array_key_exists('parent', $url_parts) && !empty($url_parts['parent'])) {
	    $controller = $url_parts['parent'];
      }
    }

    return $controller;

  } // process_controllers

  //**************************************************************************************//
  // Process the page base.
  function process_page_base ($controller = '') {

    $page_base = BASE_URL;

    if (!empty($controller)) {
	  $page_base = BASE_URL . $controller . '/';
    }

    return $page_base;

  } // process_page_base

} // requestFiltering

?>