<?php

/**
 * Colorspace Helpers Stuff (colorspace_helpers.class.php) (c) by Jack Szwergold
 *
 * Colorspace Helpers Stuff is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: https://www.szwergold.com
 * e: jackszwergold@icloud.com
 *
 * Created: 2015-04-29, js
 * Version: 2015-04-29, js: creation
 *          2015-04-29, js: development & cleanup
 *          2015-04-30, js: development & cleanup
 *          2015-05-06, js: ditching all of that XML in favor of pure JSON
 *
 */

//**************************************************************************************//
// Require (once) the parent conversions class.

require_once('colorspace_conversions.class.php');

//**************************************************************************************//
// Here is where the magic happens!

class Helpers extends Conversions {

  /**************************************************************************************/

  public function read_pms_data () {

    // Get the data from the JSON file.
    $json = $this->fetch_pms_JSON('lib/data/pms_to_rgb.json');

    // If the JSON variable is empty, generate a new JSON file and load that.
    if (empty($json)) {

      // Parse the PMS HTML data.
      $data = $this->parse_pms_HTML();

      // Roll through all of the parsed values and assign to a new array.
      $json = $this->fetch_pms_JSON('lib/data/pms_to_rgb.json', $data);

    }

    return $json;

  } // read_pms_data

  /**************************************************************************************/

  public function parse_pms_HTML ($extra_fields = array()) {

    // Load the raw PMS data HTML file.
    $raw = file('lib/data/pms_to_rgb.html');

    // Load the raw PMS data HTML file.
    $data = array();
    foreach ($raw as $key => $value) {
      $split = preg_split('~  +~', $value, -1, PREG_SPLIT_NO_EMPTY);
      array_map('trim', $split);
      if (count($split) > 1) {
        $data[] = $split;
      }
    }

    // Set the valid key names.
    $valid_values = array('red', 'green', 'blue', 'hex');
    $valid_values = array_merge($valid_values, $extra_fields);

    // Get the key name by shifting the first item off of the array.
    $key_names = array_intersect(array_shift($data),  $valid_values);

    // Roll through all of the parsed values and assign to a new array.
    $ret = array();
    foreach ($data as $parent_key => $parent_value) {
      foreach ($parent_value as $child_key => $child_value) {
        $pms_key = ucwords(preg_replace('~ +~', '_', $parent_value[0]));
        if (array_key_exists($child_key, $key_names)) {
          $ret[$pms_key][$key_names[$child_key]] = $child_value;
        }
      }

      // Convert the RGB value to gray.
      $gray_value = $this->rgb_to_gray($ret[$pms_key], 'standard');

      // Set the gray hex and percentage into the array.
      $ret[$pms_key]['gray_hex'] = $this->rgb_to_hex($gray_value);
      $ret[$pms_key]['gray_percentage'] = $this->gray_percentage($gray_value);
    }

    return $ret;

  } // parse_pms_HTML

  /**************************************************************************************/

  public function fetch_pms_JSON ($filename, $data = array()) {

    $ret = FALSE;

    // If the '$filename' value is empty.
    if (empty($filename)) {
      return $ret;
    }

    // Set the boolean for file exists.
    $file_exists = file_exists($filename);

    // Set the basic time values.
    $modified_time = $file_exists ? filemtime($filename) : 0;
    $current_time = time();

    // Calculate the time difference in minutes.
    $diff_time_minutes = (($current_time - $modified_time) / 60);

    // Set the boolean for file expired.
    $file_expired = ($diff_time_minutes > 60);

    if ($file_expired || !empty($data)) {

      // Cache the pixel blocks to a JSON file.
      $file_handle = fopen($filename, 'w');
      // fwrite($file_handle, json_encode((object) $data, JSON_PRETTY_PRINT));
      // fwrite($file_handle, json_encode((object) $data));

      // Encode the data into JSON format.
      $json = json_encode((object) $data);
      $json = str_replace('\/','/', $json);
      $json = prettyPrint($json);

      // Write the data to a file.
      fwrite($file_handle, $json);
      fclose($file_handle);

      $ret = $data;

    }
    else if ($file_exists) {

      // Return the JSON from the file.
      $ret = json_decode(file_get_contents($filename), TRUE);

    }

    return $ret;

  } // fetch_pms_JSON

} // Display

?>