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
// Require the basics.

require_once BASE_FILEPATH . '/lib/Parsedown.php';

//**************************************************************************************//
// The beginnings of a content creation class.

class requestFiltering {

  private $markdown_path = "markdown/";
  private $redirect_header = "HTTP/1.1 301 Moved Permanently";

  public function __construct() {
    // Not currently used. Setting this for future use.
  } // __construct

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
  // Process the page base suffix.
  function process_page_base_suffix ($JSON_MODE = false) {

    return $JSON_MODE ? '?json' : '';

  } // process_page_base_suffix

  //**************************************************************************************//
  // Process the URL parts.
  function process_url_parts ($params = array()) {

	$controller_parts = array('parent', 'child', 'grandchild', 'greatgrandchild');
    $url_parts = array();
	foreach ($controller_parts as $part) {
	  if (array_key_exists($part, $params) && !empty($params[$part]) && $params[$part] != 'index') {
		$url_parts[$part] = rawurlencode($params[$part]);
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

  //**************************************************************************************//
  //**************************************************************************************//
  //**************************************************************************************//
  // Set the markdown file.
  function process_markdown_file ($params = array()) {

    // Assume the full path given is for an actual Markdown file.
    $markdown_file = '';
    if (count($params) > 0) {
      $markdown_file = $this->markdown_path . join("/", $params) . ".md";
    }

    // If that full path for a file doesn’t exist do the following.
    if (!file_exists($markdown_file)) {

      // For this test we are assuming of the file doesn’t exist, it might be a directory.
      $markdown_offset = 0;
      $markdown_file = $this->markdown_path . join("/", $params) . "/index.md";

      // Test if the file exists or not and test up the parent path tree.
      if (!file_exists($markdown_file)) {
        for ($markdown_offset = -1; $markdown_offset >= -count($params); $markdown_offset--) {
          $markdown_sliced = array_slice($params, 0, $markdown_offset);
          $markdown_file = $this->markdown_path . join("/", $markdown_sliced) . "/index.md";
          if (file_exists($markdown_file)) {
            break;
          }
        }
      }

      // If the file doesn’t exist, just go to the next parent directory.
      if (count($params) > 0 && file_exists($markdown_file)) {
        $markdown_sliced = array_slice($params, 0, $markdown_offset);
        $redirect_path = join("/", $markdown_sliced);
        if ($markdown_offset < 0 && file_exists($markdown_file)) {
          header($this->redirect_header);
          header("Location: " . BASE_URL .  $redirect_path);
        }
      }

    }

    return $markdown_file;

  } // process_markdown_file

  //**************************************************************************************//
  // Set the page title.
  function process_page_title ($params = array()) {
    global $SITE_TITLE;

    // Set the first fragment to be the site title.
    $title_parts = array($SITE_TITLE);

    // Roll through each param and make all words uppercase.
    foreach($params as $param_key => $param_value) {
      $param_value = preg_replace('/_/', " ", $param_value);
      $title_parts[$param_key] = ucwords($param_value);
    }

    // Join the title with a backslash.
    $ret = join(" / ", $title_parts);

    return $ret;

  } // process_page_title

} // requestFiltering

?>