<?php

/**
 * Markdown Helper (markdownHelper.class.php) (c) by Jack Szwergold
 *
 * Markdown Helper is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2016-07-29, js
 * Version: 2016-07-29, js: creation
 *          2016-07-29, js: development
 *
 */

//**************************************************************************************//
// Require the basics.

require_once BASE_FILEPATH . '/lib/Parsedown.php';

//**************************************************************************************//
// The beginnings of a content creation class.

class markdownHelper {

  private $markdown_path = "markdown/";
  private $redirect_header = "HTTP/1.1 301 Moved Permanently";

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

} // markdownHelper

?>