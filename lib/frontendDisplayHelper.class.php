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

require_once BASE_FILEPATH . '/lib/Mosaic.class.php';


//**************************************************************************************//
// The beginnings of a front end display helper class.

class frontendDisplayHelper {

  private $controller = '';
  private $page_base = '';
  private $page_base_suffix = '';
  private $page_title = '';
  private $count = 1;

  private $url_parts = array();
  private $VIEW_MODE = null;
  private $DEBUG_MODE = FALSE;
  private $html_content = '';
  private $json_content = '';


  //**************************************************************************************//
  // Set the selected controller.
  public function setController ($value) {

    if (!empty($value)) {
      $this->controller = $value;
    }

  } // setController


  //**************************************************************************************//
  // Set the page base.
  public function setPageBase ($value) {

    if (!empty($value)) {
      $this->page_base = $value;
    }

  } // setPageBase


  //**************************************************************************************//
  // Set the page base suffix.
  public function setPageBaseSuffix ($value) {

    if (!empty($value)) {
      $this->page_base_suffix = $value;
    }

  } // setPageBaseSuffix


  //**************************************************************************************//
  // Set the count.
  public function setCount ($value) {

    if (!empty($value)) {
      $this->count = $value;
    }

  } // setCount


  //**************************************************************************************//
  //**************************************************************************************//
  //**************************************************************************************//
  // Get the view mode.
  public function getViewMode () {

    return $this->VIEW_MODE;

  } // getViewMode


  //**************************************************************************************//
  // Get the page title.
  public function getPageTitle () {

    return $this->page_title;

  } // getPageTitle


  //**************************************************************************************//
  // Get the URL parts.
  public function getURLParts () {

    return $this->url_parts;

  } // getURLParts


  //**************************************************************************************//
  // Get the HTML content.
  public function getHTMLContent () {

    return $this->html_content;

  } // getHTMLContent


  //**************************************************************************************//
  // Get the JSON content.
  public function getJSONContent () {

    return $this->json_content;

  } // getJSONContent


  //**************************************************************************************//
  //**************************************************************************************//
  //**************************************************************************************//
  // Filter the view mode.
  private function filterViewMode ($mode = null, $mode_options = null) {
    global $SITE_DEFAULT_CONTROLLER;

    if (!empty($mode) && $mode == 'random') {
      $mode_keys = array_keys($mode_options);
      shuffle($mode_keys);
      $mode = $mode_keys[0];
    }
    else if (!empty($mode) && !array_key_exists($mode, $mode_options)) {
      $mode = $SITE_DEFAULT_CONTROLLER;
    }

    return $mode;

  } // filterViewMode


  public function initContent ($DEBUG_MODE = FALSE) {

  //**************************************************************************************//
  // Set the view mode.
  $this->VIEW_MODE = $this->controller;

  //**************************************************************************************//
  // Set the debug mode.
  $this->DEBUG_MODE = $DEBUG_MODE;

  //**************************************************************************************//
  // Set an array of mode options.

    $mode_options = array();

    $mode_options['micro']['width'] = 6;
    $mode_options['micro']['height'] = 6;
    $mode_options['micro']['block_size'] = 10;
    $mode_options['micro']['how_many'] = 40;
    $mode_options['micro']['block_display'] = 10;
    $mode_options['micro']['json_display'] = 40;

    $mode_options['tiny']['width'] = 12;
    $mode_options['tiny']['height'] = 12;
    $mode_options['tiny']['block_size'] = 10;
    $mode_options['tiny']['how_many'] = 24;
    $mode_options['tiny']['block_display'] = 8;
    $mode_options['tiny']['json_display'] = 24;

    $mode_options['small']['width'] = 23;
    $mode_options['small']['height'] = 23;
    $mode_options['small']['block_size'] = 10;
    $mode_options['small']['how_many'] = 9;
    $mode_options['small']['block_display'] = 3;
    $mode_options['small']['json_display'] = 9;

    $mode_options['large']['width'] = 46;
    $mode_options['large']['height'] = 46;
    $mode_options['large']['block_size'] = 10;
    $mode_options['large']['how_many'] = 3;
    $mode_options['large']['block_display'] = 1;
    $mode_options['large']['json_display'] = 3;

    $mode_options['mega']['width'] = 72;
    $mode_options['mega']['height'] = 72;
    $mode_options['mega']['block_size'] = 10;
    $mode_options['mega']['how_many'] = 1;
    $mode_options['mega']['block_display'] = 1;
    $mode_options['mega']['json_display'] = 1;

    //**************************************************************************************//
    // Set the view mode.

    $this->VIEW_MODE = $this->filterViewMode($this->VIEW_MODE, $mode_options);

    //**************************************************************************************//
    // Set the image directory.

    $image_dir = 'images/';

    //**************************************************************************************//
    // Check if there is an image directory. If not? Exit.

    if (!is_dir($image_dir)) {
      die('Sorry. Image directory not found.');
    }

    //**************************************************************************************//
    // Process the images in the directory.

    $skip_files = array('..', '.', '.DS_Store', 'ignore');
    $image_files = scandir($image_dir);
    $image_files = array_diff($image_files, $skip_files);

    if (empty($image_files)) {
      die('Sorry. No images found.');
    }

    $raw_image_files = array();
    foreach ($image_files as $image_file_key => $image_file_value) {
      $raw_image_files[$image_file_key] = $image_dir . $image_file_value;
    }

    //**************************************************************************************//
    // Shuffle the image files.

    shuffle($raw_image_files);

    //**************************************************************************************//
    // Slice off a subset of the image files.

    $image_files = array_slice($raw_image_files, 0, $mode_options[$this->VIEW_MODE]['how_many']);

    //**************************************************************************************//
    // Init the class and roll through the images.

    $ProcessingClass = new imageMosaic();

    // Init the items array.
    $items = array();

    // Loop through the image files array.
    foreach ($image_files as $image_file) {

      // Set the options for the image processing.
      $ProcessingClass->set_image($image_file, $mode_options[$this->VIEW_MODE]['width'], $mode_options[$this->VIEW_MODE]['height'], $mode_options[$this->VIEW_MODE]['block_size']);
      $ProcessingClass->debug_mode(FALSE);
      $ProcessingClass->row_flip_horizontal(FALSE);
      $ProcessingClass->set_row_delimiter(NULL);
      $ProcessingClass->set_generate_images(TRUE);
      $ProcessingClass->set_overlay_image(TRUE);

      // Process the image and add it to the items array.
      $processed_image = $ProcessingClass->process_image();
      $items[$image_file]['blocks'] = $processed_image['blocks'];
      $items[$image_file]['json'] = $processed_image['json'];

    } // foreach

    //**************************************************************************************//
    // Use 'array_filter' to filter out the empty images.

    $items = array_filter($items);

    //**************************************************************************************//
    // Place the images in <li> tags.

    // Init the image item and related json array.
    $image_item_array = $image_json_array = array();

    // Init the counter value.
    $count = 0;

    // Loop through the artworks array.
    foreach ($items as $file => $image) {

      // Set the image item array value.
      if ($count < $mode_options[$this->VIEW_MODE]['block_display']) {
        $image_item_array[$file] = sprintf('<li><div class="Padding">%s</div><!-- .Padding --></li>', $image['blocks']);
      }

      // Set the image json array value.
      if ($count < $mode_options[$this->VIEW_MODE]['json_display']) {
        $image_json_array[$file] = $image['json'];
      }

      // Increment the counter.
      $count++;

    } // foreach

    // Set the body content.
    $this->html_content = sprintf('<ul>%s</ul>', implode('', $image_item_array));

    // Convert the JSON back to an object.
    $json_data_array = array();
    foreach ($image_json_array as  $image_json_value) {
      $json_data_array['content'][] = json_decode($image_json_value);
    }
    $json_data_array['count'] = count($json_data_array['content']);
    $json_data_array['total'] = count($image_json_array);

    // Now merge the JSON data object back into the parent image object.
    $image_object = $ProcessingClass->build_content_object($json_data_array, $this->page_base, $this->page_base_suffix, array_keys($mode_options), 'images');

    // Process the JSON content.
    $this->json_content = $ProcessingClass->json_encode_helper($image_object, $DEBUG_MODE);

  } // initContent


} // frontendDisplayHelper

?>