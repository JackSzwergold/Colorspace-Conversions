<?php

/**
 * Colorspace Class (frontendDisplayHelper.class.php) (c) by Jack Szwergold
 *
 * Colorspace Class is licensed under a
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
// The beginnings of a front end display helper class.
class colorspaceClass {

  public $controller = null;
  public $url_parts = array();

  public $page_title = null;

  public $DisplayClass = null;

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
    // Set the final return value.
    $ret = array($colorspace, $value);

    //**************************************************************************************//
    // Return the final return value.
    return $ret;

  } // initContent

  //**************************************************************************************//
  // A function to render infobox content.
  public function infoboxContent($colorspace, $value) {

    //**************************************************************************************//
    // Do something.
    list($ret, $css, $hex) = $this->init($colorspace, $value);

    //**************************************************************************************//
    // Return the final return value.
    return array($ret, $css, $hex);

  } // infoboxContent

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

  public $rgb_to_cmy_map = array();
  public $rgb_to_shift_map = array();
  public $rgb_to_gray_luma_map = array();

  public $rgb_components = array();
  public $cmy_components = array();
  public $cmyk_components = array();

  public $hsl_components = array();
  public $hsv_components = array();

  public $cmyk_to_rgb_colorspace_image = 'lib/data/cmyk_to_rgb_colorspace.png';

  public $max_rgb_value = 0;

  /**************************************************************************************/
  // The constructor.
  public function __construct() {
    $this->init_values();
  } // __construct

  /**************************************************************************************/
  // The init values function.
  public function init_values() {

    /************************************************************************************/
    // Init the max color value.
    $this->max_rgb_value = 255;

    /************************************************************************************/
    // Init the RGB to CMY map array.
    $this->rgb_to_cmy_map['red'] = 'cyan';
    $this->rgb_to_cmy_map['green'] = 'magenta';
    $this->rgb_to_cmy_map['blue'] = 'yellow';

    /************************************************************************************/
    // Init the RGB to shift map array.
    $this->rgb_to_shift_map['red'] = 16;
    $this->rgb_to_shift_map['green'] = 8;
    $this->rgb_to_shift_map['blue'] = 0;

    /************************************************************************************/
    // Init the RGB to gray luma standard map array.
    $this->rgb_to_gray_luma_map['standard'] = array();
    $this->rgb_to_gray_luma_map['standard']['red'] = 1;
    $this->rgb_to_gray_luma_map['standard']['green'] = 1;
    $this->rgb_to_gray_luma_map['standard']['blue'] = 1;

    /************************************************************************************/
    // Init the RGB to gray luma generic map array.
    $this->rgb_to_gray_luma_map['generic'] = array();
    $this->rgb_to_gray_luma_map['generic']['red'] = 0.3086;
    $this->rgb_to_gray_luma_map['generic']['green'] = 0.6094;
    $this->rgb_to_gray_luma_map['generic']['blue'] = 0.0820;

    /************************************************************************************/
    // Init the RGB to gray luma rec601 map array.
    $this->rgb_to_gray_luma_map['rec601'] = array();
    $this->rgb_to_gray_luma_map['rec601']['red'] = 0.2989;
    $this->rgb_to_gray_luma_map['rec601']['green'] = 0.5870;
    $this->rgb_to_gray_luma_map['rec601']['blue'] = 0.1140;

    /************************************************************************************/
    // Init the RGB to gray luma rec709 map array.
    $this->rgb_to_gray_luma_map['rec709'] = array();
    $this->rgb_to_gray_luma_map['rec709']['red'] = 0.2126;
    $this->rgb_to_gray_luma_map['rec709']['green'] = 0.7152;
    $this->rgb_to_gray_luma_map['rec709']['blue'] = 0.0722;

    /************************************************************************************/
    // Init the RGB component names.
    $this->rgb_components = array('red', 'green', 'blue');

    /************************************************************************************/
    // Init the CMY component names.
    $this->cmy_components = array('cyan', 'magenta', 'yellow');

    /************************************************************************************/
    // Init the CMYK component names.
    $this->cmyk_components = array('cyan', 'magenta', 'yellow', 'black');

    /************************************************************************************/
    // Init the HSL component names.
    $this->hsl_components = array('hue', 'saturation', 'lightness');

    /************************************************************************************/
    // Init the HSV component names.
    $this->hsv_components = array('hue', 'saturation', 'value');

  } // init_values

  /**************************************************************************************/
  // The RGB to HEX function.
  public function rgb_to_hex($rgb_value = array()) {
    return sprintf("#%02X%02X%02X", $rgb_value['red'], $rgb_value['green'], $rgb_value['blue']);
  } // rgb_to_hex

  /**************************************************************************************/
  // The RGB to CMYK function.
  public function rgb_to_cmyk($rgb_value = array()) {
    return $this->cmy_to_cmyk($this->rgb_to_cmy($rgb_value));
  } // rgb_to_cmyk

  /**************************************************************************************/
  // The RGB to CMY function.
  public function rgb_to_cmy($rgb_value = array()) {

    /************************************************************************************/
    // Init the basic values.
    $round_to = 6;

    /************************************************************************************/
    // Roll through the RGB to CMY values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_to_cmy_map as $rgb_name => $cmy_name) {
      $ret[$cmy_name] = round((1 - ($rgb_value[$rgb_name] / $this->max_rgb_value)), $round_to) / 100;
    }

    /************************************************************************************/
    // Return the final values.
    return $ret;

  } // rgb_to_cmy

  /**************************************************************************************/
  // The RGB to HSL function.
  public function rgb_to_hsl($rgb_value = array()) {

    /************************************************************************************/
    // Init the basic values.
    $round_to = 6;
    $hue = $saturation = $lightness = 0;

    /************************************************************************************/
    // Calculate percentages for all three colors.
    foreach ($this->rgb_components as $rgb_component) {
      $$rgb_component = $rgb_value[$rgb_component] / $this->max_rgb_value;
    } // foreach

    /************************************************************************************/
    // Get the max and min values of the RGB values.
    $max_rgb = max($red, $green, $blue);
    $min_rgb = min($red, $green, $blue);

    /************************************************************************************/
    // Calculate lightness by adding max and min values and dividing by two.
    $lightness = ($max_rgb + $min_rgb) / 2;

    /************************************************************************************/
    // Get the chroma which is the delta between max and min values.
    $chroma = $max_rgb - $min_rgb;

    /************************************************************************************/
    // Calculate the hue and saturation.
    if ($chroma == 0) {

      /**********************************************************************************/
      // If the chroma is 0, the max_rgb and min_rgb are the same, so then the hue and saturation is 0.
      $hue = $saturation = 0;

    } // if
    else {

      /**********************************************************************************/
      // If the chroma is not 0, then we calculate the saturation like this.
      $saturation = $lightness > 0.5 ? ($chroma / (2 - $max_rgb - $min_rgb)) : ($chroma / ($max_rgb + $min_rgb));

      /**********************************************************************************/
      // Calculate the hue.
      switch($max_rgb) {
        case $red:
          $hue = fmod((($green - $blue) / $chroma), 6) * 60;
          if ($blue > $green) {
            $hue += 360;
          } // if
          break;
        case $green:
          $hue = (($blue - $red) / $chroma + 2) * 60;
          break;
        case $blue:
          $hue = (($red - $green) / $chroma + 4) * 60;
          break;
      } // switch

    } // else

    /************************************************************************************/
    // Round the final values and assign them to an array.
    $ret = array();
    $ret['hue'] = round($hue, $round_to);
    $ret['saturation'] = round($saturation, $round_to) * 100;
    $ret['lightness'] = round($lightness, $round_to) * 100;

    /************************************************************************************/
    // Return the final values.
    return $ret;

  } // rgb_to_hsl

  /**************************************************************************************/
  // The RGB to HSV function.
  public function rgb_to_hsv($rgb_value = array()) {

    /************************************************************************************/
    // Init the basic values.
    $round_to = 6;
    $hue = $saturation = $value = 0;

    /************************************************************************************/
    // Calculate percentages for all three colors.
    foreach ($this->rgb_components as $rgb_component) {
      $$rgb_component = $rgb_value[$rgb_component] / $this->max_rgb_value;
    } // foreach

    /************************************************************************************/
    // Get the max and min values of the RGB values.
    $max_rgb = max($red, $green, $blue);
    $min_rgb = min($red, $green, $blue);

    /************************************************************************************/
    // Get the chroma which is the delta between max and min values.
    $chroma = $max_rgb - $min_rgb;

    /************************************************************************************/
    // Calculate value.
    $value = $max_rgb;

    /************************************************************************************/
    // If the chroma is 0, the max_rgb and min_rgb are the same, so then the hue and saturation is 0.
    if ($chroma == 0) {

      /**********************************************************************************/
      // If the chroma is 0, the max_rgb and min_rgb are the same, so then the hue and saturation is 0.
      $hue = $saturation = 0;

    } // if
    else {

      /**********************************************************************************/
      // If the chroma is not 0, then we calculate the saturation like this.
      $saturation = ($chroma / $max_rgb);

      /**********************************************************************************/
      // Calculate hue.
      if ($red == $min_rgb) {
        $hue = 3 - (($green - $blue) / $chroma);
      } // if
      else if ($blue == $min_rgb) {
        $hue = 1 - (($red - $green) / $chroma);
      } // else if
      else { // $green == $min_rgb
        $hue = 5 - (($blue - $red) / $chroma);
      } // else

    } // else

    /************************************************************************************/
    // Round the final values and assign them to an array.
    $ret = array();
    $ret['hue'] = round($hue * 60, $round_to);
    $ret['saturation'] = round($saturation, $round_to) * 100;
    $ret['value'] = round($value, $round_to) * 100;

    /************************************************************************************/
    // Return the final values.
    return $ret;

  } // rgb_to_hsv

  /**************************************************************************************/
  // The RGB to gray function.
  public function rgb_to_gray($rgb_value = array(), $luma_type = 'standard') {

    $gray_array = array();
    foreach ($this->rgb_components as $rgb_name) {
      $gray_array[$rgb_name] = $rgb_value[$rgb_name] * $this->rgb_to_gray_luma_map[$luma_type][$rgb_name];
    } // foreach

    $gray = round(array_sum(array_values($gray_array)) / count($gray_array));

    /************************************************************************************/
    // Roll through the RGB shift map values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_components as $rgb_name) {
      $ret[$rgb_name] = $gray;
    } // foreach

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // rgb_to_gray

  /**************************************************************************************/
  // The RGB invert function.
  public function rgb_invert($rgb_value = array()) {

    /************************************************************************************/
    // Invert the color by subtracting the value from the max RGB value.
    foreach ($this->rgb_components as $rgb_name) {
      $$rgb_name = $this->max_rgb_value - $rgb_value[$rgb_name];
    } // foreach

    /************************************************************************************/
    // Roll through the RGB shift map values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_components as $rgb_name) {
      $ret[$rgb_name] = $$rgb_name;
    } // foreach

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // rgb_invert

  /**************************************************************************************/
  // The gray percentage function.
  public function gray_percentage ($gray_value = array()) {

    /************************************************************************************/
    // Init the basic values.
    $round_to = 6;

    /************************************************************************************/
    // Calculate the gray percentage.
    $ret = round((array_sum(array_values($gray_value)) / count($gray_value)) / $this->max_rgb_value, $round_to);

    /************************************************************************************/
    // Return the final percentage.
    return round($ret * 100, $round_to);

  } // gray_percentage

  /**************************************************************************************/
  // The CMYK to RGB function.
  public function cmyk_to_rgb ($cmyk_value = array()) {

    /************************************************************************************/
    // Calculate the X and Y coordinates on the image.
    $x_coor = round($cmyk_value['yellow'] / 5) * 21 + round($cmyk_value['cyan'] / 5);
    $y_coor = round($cmyk_value['black'] / 5) * 21 + round($cmyk_value['magenta'] / 5);

    /************************************************************************************/
    // This is the image with CMYK to RGB color values.
    $cmyk_map = ImageCreateFromPng($this->cmyk_to_rgb_colorspace_image);

    /************************************************************************************/
    // This 'eyedrops' the RGB value from the above table.
    $rgb_value = ImageColorAt($cmyk_map, $x_coor, $y_coor);

    /************************************************************************************/
    // Roll through the RGB shift map values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_to_shift_map as $rgb_name => $shift_value) {
      $ret[$rgb_name] = ($rgb_value >> $shift_value) & 0xFF;
    } // foreach

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // cmyk_to_rgb

  /**************************************************************************************/
  // The CMY to CMYK function.
  public function cmy_to_cmyk ($cmy_value = array()) {

    /************************************************************************************/
    // Init the basic values.
    $round_to = 6;

    /************************************************************************************/
    // Init the default black level to 1.
    $cmy_value['black'] = 1;

    /************************************************************************************/
    // Roll through all of the CMY components to see if they are pure black (1) or not.
    foreach ($this->cmy_components as $cmy_component) {
      $cmy_value['black'] = ($cmy_value[$cmy_component] < $cmy_value['black']) ? $cmy_value[$cmy_component] : $cmy_value['black'];
    } // foreach

    /************************************************************************************/
    // If the CMY black value is pure black do this.
    if ($cmy_value['black'] == 1) {
      foreach ($this->cmy_components as $cmy_component) {
        $cmy_value[$cmy_component] = 0;
      } // foreach
    } // if
    else {
      foreach ($this->cmy_components as $cmy_component) {
        $cmy_value[$cmy_component] = round(((($cmy_value[$cmy_component] - $cmy_value['black']) / (1 - $cmy_value['black']) * 100)), $round_to);
      } // foreach
    } // else

    /************************************************************************************/
    // Set the final CMY black value.
    $cmy_value['black'] = round(($cmy_value['black'] * 100), $round_to);

    /************************************************************************************/
    // Return the final return value.
    return $cmy_value;

  } // cmy_to_cmyk

  /**************************************************************************************/
  // The HSL to RGB function.
  public function hsl_to_rgb ($hsl_value = array()) {

    /************************************************************************************/
    // Init the basic values.
    $round_to = 2;
    $red = $green = $blue = 0;

    /************************************************************************************/
    // Extract the HSL values.
    list($hue, $saturation, $lightness) = array_values($hsl_value);

    /************************************************************************************/
    // Convert degrees and percentages back to decimals.
    $hue_degrees = $hue / 360;
    $saturation = $saturation / 100;
    $lightness = $lightness / 100;

    /************************************************************************************/
    // Six sides in a hexagon HSL model, so multiply hue value by 6.
    $hue_degrees = $hue_degrees * 6;

    /************************************************************************************/
    // Round to the floor of the value to determine what side of the hexagon the value is on.
    $hue_floor = floor($hue_degrees);

    /************************************************************************************/
    // Set the chroma.
    $chroma = (1 - abs(2 * $lightness - 1)) * $saturation;

    /************************************************************************************/
    // Set related temporary values.
    $temp_1 = $chroma * (1 - abs(fmod(($hue / 60), 2) - 1));
    $temp_2 = ($lightness - ($chroma / 2));

    /************************************************************************************/
    // Assign the RGB values based on what side of the hexagon we are on.
    if ($hue_floor == 0) {
      $red = $chroma;
      $green = $temp_1;
      $blue = 0;
    } // if
    else if ($hue_floor == 1) {
      $red = $temp_1;
      $green = $chroma;
      $blue = 0;
    } // else if
    else if ($hue_floor == 2) {
      $red = 0;
      $green = $chroma;
      $blue = $temp_1;
    } // else if
    else if ($hue_floor == 3) {
      $red = 0;
      $green = $temp_1;
      $blue = $chroma;
    } // else if
    else if ($hue_floor == 4) {
      $red = $temp_1;
      $green = 0;
      $blue = $chroma;
    } // else if
    else if ($hue_floor == 5) {
      $red = $chroma;
      $green = 0;
      $blue = $temp_1;
    } // else if

    /************************************************************************************/
    // Round the final values and assign them to an array.
    $ret = array();
    foreach ($this->rgb_components as $rgb_component) {
      $ret[$rgb_component] = round(($$rgb_component + $temp_2) * $this->max_rgb_value, $round_to);
    } // foreach

    /************************************************************************************/
    // Return the final values.
    return $ret;

  } // hsl_to_rgb

  /**************************************************************************************/
  // The HSV to RGB function.
  public function hsv_to_rgb($hsv_value = null) {

    /************************************************************************************/
    // Extract the HSV values.
    list($hue, $saturation, $value) = array_values($hsv_value);

    /************************************************************************************/
    // Init the basic values.
    $round_to = 0;
    $red = $green = $blue = 0;

    /************************************************************************************/
    // Convert degrees and percentages back to decimals.
    $hue = $hue / 360;
    $saturation = $saturation / 100;
    $value = $value / 100;

    /************************************************************************************/
    // If saturation is 0, then the color is grey and that is all she wrote.
    if ($saturation == 0) {
      $red = $green = $blue = $value;
    } // if
    else {

      /**********************************************************************************/
      // Six sides in a hexagon HSV model, so multiply hue value by 6.
      $hue = $hue * 6;

      /**********************************************************************************/
      // Round to the floor of the value to determine what side of the hexagon the value is on.
      $hue_floor = floor($hue);

      /**********************************************************************************/
      // Calculate the temp values.
      $temp_1 = $value * (1 - $saturation);
      $temp_2 = $value * (1 - $saturation * ($hue - $hue_floor));
      $temp_3 = $value * (1 - $saturation * (1 - ($hue - $hue_floor)));

      /**********************************************************************************/
      // Assign the RGB values based on what side of the hexagon we are on.
      if ($hue_floor == 0) {
        $red = $value;
        $green = $temp_3;
        $blue = $temp_1;
      } // if
      else if ($hue_floor == 1) {
        $red = $temp_2;
        $green = $value;
        $blue = $temp_1;
      } // else if
      else if ($hue_floor == 2) {
        $red = $temp_1;
        $green = $value;
        $blue = $temp_3;
      } // else if
      else if ($hue_floor == 3) {
        $red = $temp_1;
        $green = $temp_2;
        $blue = $value;
      } // else if
      else if ($hue_floor == 4) {
        $red = $temp_3;
        $green = $temp_1;
        $blue = $value;
      } // else if
      else if ($hue_floor == 5) {
        $red = $value;
        $green = $temp_1;
        $blue = $temp_2;
      } // else if

    }

    /**********************************************************************************/
    // Round the final values and assign them to an array.
    $ret = array();
    foreach ($this->rgb_components as $rgb_component) {
      $ret[$rgb_component] = round($$rgb_component * $this->max_rgb_value, $round_to);
    }

    /**********************************************************************************/
    // Return the final values.
    return $ret;

  } // hsv_to_rgb

  /**************************************************************************************/
  // The HEX to RGB function.
  public function hex_to_rgb($hex_value = null) {

    /************************************************************************************/
    // Convert the HEX value into an RGB array.
    $raw_rgb_array = array_map('hexdec', str_split($hex_value, 2));

    /************************************************************************************/
    // Round the final values and assign them to an array.
    $ret = array();

    if (!empty($raw_rgb_array)) {
      foreach ($this->rgb_components as $rgb_key => $rgb_component) {
        $ret[$rgb_component] = $raw_rgb_array[$rgb_key];
      }
    }

    /************************************************************************************/
    // Return the final values.
    return $ret;

  } // hex_to_rgb

  /**************************************************************************************/
  // The PMS to RGB function.
  public function pms_to_rgb($pms_value = null) {

    /************************************************************************************/
    // Round the final values and assign them to an array.
    $ret = array();

    /************************************************************************************/
    // Get the PMS to RGB map loaded.
    $pms_data = $this->read_pms_data();

    /************************************************************************************/
    // Simple validation.
    if (array_key_exists($pms_value, $pms_data)) {
      $ret = $pms_data[$pms_value];
    } // if
    else {
      foreach ($this->rgb_components as $rgb_key => $rgb_component) {
        $ret[$rgb_component] = 00;
      } // foreach
    } // else

    /************************************************************************************/
    // Return the final values.
    return $ret;

  } // pms_to_rgb

/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/

  /**************************************************************************************/
  // The read PMS data function.
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
  // The parse PMS HTML function.
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
  // The parse PMS JSON function.
  public function fetch_pms_JSON ($filename, $data = array()) {

    $ret = FALSE;

    /************************************************************************************/
    // If the '$filename' value is empty.
    if (empty($filename)) {
      return $ret;
    }

    /************************************************************************************/
    // Set the boolean for file exists.
    $file_exists = file_exists($filename);

    /************************************************************************************/
    // Set the basic time values.
    $modified_time = $file_exists ? filemtime($filename) : 0;
    $current_time = time();

    /************************************************************************************/
    // Calculate the time difference in minutes.
    $diff_time_minutes = (($current_time - $modified_time) / 60);

    /************************************************************************************/
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

    } // if
    else if ($file_exists) {

      /**********************************************************************************/
      // Return the JSON from the file.
      $ret = json_decode(file_get_contents($filename), TRUE);

    } // else if

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // fetch_pms_JSON

/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/

  public $rgb = NULL;
  public $gray = NULL;
  public $gray_percentage = 0;

  public $gray_text_cutoff = 36;

  public $hex = NULL;
  public $hex_inverted = NULL;
  public $hex_gray = NULL;
  public $hex_gray_inverted = NULL;

  public $pms_json = 'lib/data/pms_to_rgb.json';

  public $rgb_span = 255;
  public $rgb_step = 50;

  public $cmyk_span = 100;
  public $cmyk_step = 20;

  /**************************************************************************************/
  // The init method.
  public function init($colorspace = NULL, $value = NULL) {

    /************************************************************************************/
    // Init the basics.
    $ret = null;
    $rgb_array = array();

    /************************************************************************************/
    // Do something.
    if ($colorspace == 'rgb') {
      $rgb_array = $this->get_rgb_values($value);
    } // if
    else if ($colorspace == 'cmyk') {
      $cmyk_array = $this->get_cmyk_values($value);
      $rgb_array = $this->cmyk_to_rgb($cmyk_array);
    } // else if
    else if ($colorspace == 'hex') {
      $hex_array = $this->get_hex_values($value);
      $rgb_array = $this->hex_to_rgb($hex_array);
    } // else if
    else if ($colorspace == 'pms') {
      $pms_value = $this->get_pms_values($value);
      $rgb_array = $this->pms_to_rgb($pms_value);
    } // else if

    /************************************************************************************/
    // Get the final return values.
    $final_values = $this->get_color_values($rgb_array);

    /************************************************************************************/
    // Set the final return values.
    $ret = $this->set_infobox_content($final_values);

    /************************************************************************************/
    // Set the CSS for the text.
    $css = $this->gray_percentage > $this->gray_text_cutoff ? 'text-black' : 'text-white';

    /************************************************************************************/
    // Return the final return values.
    return array($ret, $css, $this->hex);

  } // init

  /**************************************************************************************/
  // The set infobox content method.
  private function set_infobox_content($final = array()) {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Set the hex infobox.
    if (isset($this->hex)) {

      /**********************************************************************************/
      // Set the text hex color based on the gray percentage.
      // $css = $this->gray_percentage > $this->gray_text_cutoff ? 'text-black' : 'text-white';

      /**********************************************************************************/
      // Build the infobox.
      foreach ($final as $key => $value) {
        $ret .=
            '<p class="m-0 p-0 text font-monospace">'
          . '<small>'
          . '<b>'
          . strtoupper($key)
          . '</b>: '
          . $value
          . '</small>'
          . '</p>'
          ;
      } // foreach

    } // if

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // set_infobox_content

  /**************************************************************************************/
  // The get RGB values method.
  private function get_rgb_values($rgb_get = null) {

    /************************************************************************************/
    // Init the basics.
    $ret = array();

    /************************************************************************************/
    // Get the RGB component names from the passed $_GET string.
    list($ret['red'], $ret['green'], $ret['blue']) = explode('_', $rgb_get);

    /************************************************************************************/
    // Loop through the RGB components.
    foreach ($this->rgb_components as $rgb_component) {
      $ret[$rgb_component] = intval($ret[$rgb_component]) > 255 ? 255 : $ret[$rgb_component];
      $ret[$rgb_component] = intval($ret[$rgb_component]) < 0 ? 0 : $ret[$rgb_component];
    } // foreach

    /************************************************************************************/
    // Return the final return values.
    return $ret;

  } // get_rgb_values

  /**************************************************************************************/
  // The get CMYK values method.
  private function get_cmyk_values($cmyk_get = null) {

    /************************************************************************************/
    // Init the basics.
    $ret = array();

    /************************************************************************************/
    // Get the CMYK component names from the passed $_GET string.
    list($ret['cyan'], $ret['magenta'], $ret['yellow'], $ret['black']) = explode('_', $cmyk_get);

    /************************************************************************************/
    // Loop through the CMYK components.
    foreach ($this->cmyk_components as $cmyk_component) {
      $ret[$cmyk_component] = intval($ret[$cmyk_component]) > 100 ? 100 : $ret[$cmyk_component];
      $ret[$cmyk_component] = intval($ret[$cmyk_component]) < 0 ? 0 : $ret[$cmyk_component];
    } // foreach

    /************************************************************************************/
    // Return the final return values.
    return $ret;

  } // get_cmyk_values

  /**************************************************************************************/
  // The get PMS values method.
  private function get_hex_values($hex_get = null) {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Check if the hex is valid.
    if (!empty($hex_get) && ctype_xdigit($hex_get)){
      $ret = $hex_get;
    } // if
    else {
      $ret = '000000';
    } // else

    return $ret;

  } // get_hex_values

  /**************************************************************************************/
  // The get PMS values method.
  private function get_pms_values($pms_get = null) {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Check if the hex is valid.
    if (!empty($pms_get)){
      $ret = $pms_get;
    } // if

    return $ret;

  } // get_pms_values

  /**************************************************************************************/
  // The build URL method.
  private function get_color_values($rgb_array = array()) {

    /************************************************************************************/
    // Sanitize the RGB array.
    $rgb_array = empty($rgb_array) ? array('red' => 0, 'green' => 0, 'blue' => 0) :  $rgb_array;

    /************************************************************************************/
    // Convert the RGB value to gray.
    $this->gray = $this->rgb_to_gray($rgb_array, 'standard');

    /************************************************************************************/
    // Convert the gray value to a percentage.
    $this->gray_percentage = $this->gray_percentage($this->gray);

    /************************************************************************************/
    // Convert the RGB value to hexadecimal.
    $this->hex = $this->rgb_to_hex($rgb_array);

    /************************************************************************************/
    // Invert the RGB and set it as a hexadecimal value for layout purposes.
    $this->hex_inverted = $this->rgb_to_hex($this->rgb_invert($rgb_array));

    /************************************************************************************/
    // Covert the RGB to gray and set it as a hexadecimal value for layout purposes.
    $this->hex_gray = $this->rgb_to_hex($this->gray);

    /************************************************************************************/
    // Invert the RGB to gray and set it as a hexadecimal value for layout purposes.
    $this->hex_gray_inverted = $this->rgb_to_hex($this->rgb_invert($this->gray));

    /************************************************************************************/
    // Convert the RGB value to CMYK.
    $cmyk_array = $this->rgb_to_cmyk($rgb_array);

    /************************************************************************************/
    // Convert the RGB value to HSL.
    $hsl = $this->rgb_to_hsl($rgb_array);

    /************************************************************************************/
    // Convert the HSL value to RGB.
    $hsl_back_to_rgb = $this->hsl_to_rgb($hsl);

    /************************************************************************************/
    // Convert the RGB value to HSV.
    $hsv = $this->rgb_to_hsv($rgb_array);

    /************************************************************************************/
    // Convert the HSV value to RGB.
    $hsv_back_to_rgb = $this->hsv_to_rgb($hsv);

    /************************************************************************************/
    // Set the CSS for text.
    $css_for_text = $this->gray_percentage > $this->gray_text_cutoff ? 'text-black' : 'text-white';

    /************************************************************************************/
    // Set all of the different values.
    $ret = array();
    $ret['hex'] = sprintf('<a href="' . BASE_URL . 'hex/%s" class="text-decoration-none %s">%s</a>', ltrim($this->hex, '#'), $css_for_text, $this->hex);
    $ret['hex_inverted'] = sprintf('<a href="' . BASE_URL . 'hex/%s" class="text-decoration-none %s">%s</a>', ltrim($this->hex_inverted, '#'), $css_for_text, $this->hex_inverted);
    $ret['hex_complimentary'] = sprintf('<a href="' . BASE_URL . 'hex/%s" class="text-decoration-none %s">%s</a>', ltrim($this->hex_inverted, '#'), $css_for_text, $this->hex_inverted);
    $ret['hex_gray'] = sprintf('<a href="' . BASE_URL . 'hex/%s" class="text-decoration-none %s">%s</a>', ltrim($this->hex_gray, '#'), $css_for_text, $this->hex_gray);
    $ret['hex_gray_inverted'] = sprintf('<a href="' . BASE_URL . 'hex/%s" class="text-decoration-none %s">%s</a>', ltrim($this->hex_gray_inverted, '#'), $css_for_text, $this->hex_gray_inverted);
    $ret['hex_gray_complimentary'] = sprintf('<a href="' . BASE_URL . 'hex/%s" class="text-decoration-none %s">%s</a>', ltrim($this->hex_gray_inverted, '#'), $css_for_text, $this->hex_gray_inverted);
    $ret['rgb'] = sprintf('R = %s, G = %s, B = %s', $rgb_array['red'], $rgb_array['green'], $rgb_array['blue']);
    $ret['gray'] = sprintf('R = %s, G = %s, B = %s', $this->gray['red'], $this->gray['green'], $this->gray['blue']);
    $ret['gray_percentage'] = sprintf('%s', $this->gray_percentage . '%');
    $ret['gray_text_cutoff'] = sprintf('%s', $this->gray_text_cutoff . '%');
    $ret['cmyk'] = sprintf('C = %s, M = %s, Y = %s, K = %s', $cmyk_array['cyan'], $cmyk_array['magenta'], $cmyk_array['yellow'], $cmyk_array['black']);
    $ret['hsl'] = sprintf('H = %s, S = %s, L = %s', $hsl['hue'] . '°', $hsl['saturation'] . '%', $hsl['lightness'] . '%');
    $ret['hsl_back_to_rgb'] = sprintf('R = %s, G = %s, B = %s', $hsl_back_to_rgb['red'], $hsl_back_to_rgb['green'], $hsl_back_to_rgb['blue']);
    $ret['hsv'] = sprintf('H = %s, S = %s, V = %s', $hsv['hue'] . '°', $hsv['saturation'] . '%', $hsv['value'] . '%');
    $ret['hsv_back_to_rgb'] = sprintf('R = %s, G = %s, B = %s', $hsv_back_to_rgb['red'], $hsv_back_to_rgb['green'], $hsv_back_to_rgb['blue']);

    return $ret;

  } // get_color_values

  /**************************************************************************************/
  // The build URL method.
  private function build_url($params = array()) {
    return BASE_URL . implode('/', $params);
  } // build_url

  /**************************************************************************************/
  // The build pixel box method.
  private function build_pixel_box($url = null, $hex = null, $text = null, $css_for_text = null) {

    /************************************************************************************/
    // Set the text.
    if (!empty($text)) {
      $text =
        sprintf('<p class="m-0 p-0 px-2 py-1 %s">', $css_for_text)
      . '<small>'
      . $text
      . '</small>'
      . '</p>'
      ;
    } // if

    /************************************************************************************/
    // Set the pixel box.
    $ret =
        sprintf('<a href="%s" class="%s">', $url, $css_for_text)
      . sprintf('<span class="PixelBox d-inline-block float-start m-0 p-0" style="background-color: %s;">', $hex)
      . $text
      . '</span><!-- .PixelBox -->'
      . '</a>'
      ;

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // build_pixel_box

  /**************************************************************************************/
  // The RGB grid method.
  public function rgb_grid() {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Prep the data.
    $span = array_fill(1, $this->rgb_span, NULL);
    $step = range(1, $this->rgb_span, $this->rgb_step);

    $rgb_array = array('red' => $step, 'green' => $step, 'blue' => $step);

    /************************************************************************************/
    // Roll through the RGB items and do something.
    foreach ($rgb_array['red'] as $red) {
      foreach ($rgb_array['green'] as $green) {
        foreach ($rgb_array['blue'] as $blue) {
          $color = array('red' => $red, 'green' => $green, 'blue' => $blue);
          $hex = $this->rgb_to_hex($color);
          $rgb = sprintf('%s_%s_%s', $red, $green, $blue);
          $url = $this->build_url(array('colorspace' => 'rgb', 'value' => $rgb));
          $ret .= $this->build_pixel_box($url, $hex);
        } // for
      } // for
    } // for

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // rgb_grid

  /**************************************************************************************/
  // The CMYK grid method.
  public function cmyk_grid() {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Prep the data.
    $cmyk_span = array_fill(1, $this->cmyk_span, NULL);
    $cmyk_step = range(1, $this->cmyk_span, $this->cmyk_step);

    /************************************************************************************/
    // Roll through the CMYK items and do something.
    for ($black = 0; $black <= ($this->cmyk_span - 20); $black += $this->cmyk_step) {
      for ($magenta = 0; $magenta <= $this->cmyk_span; $magenta += $this->cmyk_step) {
        for ($yellow = 0; $yellow <= $this->cmyk_span; $yellow += $this->cmyk_step) {
          for ($cyan = 0; $cyan <= $this->cmyk_span; $cyan += $this->cmyk_step) {
            $color = $this->cmyk_to_rgb(array('cyan' => $cyan, 'magenta' => $magenta, 'yellow' => $yellow, 'black' => $black));
            $hex = $this->rgb_to_hex($color);
            $cmyk = sprintf('%s_%s_%s_%s', $cyan, $magenta, $yellow, $black);
            $url = $this->build_url(array('colorspace' => 'cmyk', 'value' => $cmyk));
            $ret .= $this->build_pixel_box($url, $hex);
          } // for
        } // for
      } // for
    } // for

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // cmyk_grid

  /**************************************************************************************/
  // The PMS grid method.
  public function pms_grid() {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Get the PMS data.
    $pms_data = $this->read_pms_data();

    /************************************************************************************/
    // Do something.
    if (!empty($pms_data)) {

      /**********************************************************************************/
      // Sort the PMS to hex array.
      ksort($pms_data);

      /**********************************************************************************/
      // Roll through the PMS items and do something.
      foreach ($pms_data as $pms_key => $pms_value) {

        /********************************************************************************/
        // Set the CSS based on the gray percentage.
        $css = $pms_value['gray_percentage'] > $this->gray_text_cutoff ? 'text-black' : 'text-white';

        /********************************************************************************/
        // Set the RGB URL param.
        $rgb_param = sprintf('%s_%s_%s', $pms_value['red'], $pms_value['green'], $pms_value['blue']);

        /********************************************************************************/
        // Set the URL.
        $url = $this->build_url(array('colorspace' => 'pms', 'value' => $pms_key));

        /********************************************************************************/
        // Set the text to be passed back into the pixel box.
        $pixel_text = sprintf('PMS %s', ucwords(preg_replace('~_+~', ' ', $pms_key)));

        /********************************************************************************/
        // Set the pixel box.
        $ret .= $this->build_pixel_box($url, $pms_value['hex'], $pixel_text, $css);

      } // foreach

    } // if

    /************************************************************************************/
    // Return the final return value.
    return $ret;

  } // pms_grid

} // colorspaceHelper

?>