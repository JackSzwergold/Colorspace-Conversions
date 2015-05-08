<?php

/**
 * Conversions (colorspace_conversions.class.php) (c) by Jack Szwergold
 *
 * Conversions is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2015-04-29, js
 * Version: 2015-04-29, js: creation
 *          2015-04-29, js: development & cleanup
 *          2015-04-30, js: development & cleanup
 *          2015-05-01, js: adding RGB to HSL conversion logic.
 *          2015-05-02, js: adding HSL to RGB conversion logic.
 *          2015-05-02, js: adding RGB to HSV conversion logic.
 *          2015-05-03, js: adding HSV to RGB conversion logic and more cleanup.
 *          2015-05-03, js: adding RGB to gray conversion logic and more cleanup.
 *          2015-05-07, js: adding HEX to RGB conversion logic.
 *
 */

//**************************************************************************************//
// Here is where the magic happens!

class Conversions {

  public $rgb_to_cmy_map = array();
  public $rgb_to_shift_map = array();
  public $rgb_to_gray_luma_map = array();

  public $rgb_components = array();
  public $cmy_components = array();
  public $cmyk_components = array();

  public $cmyk_to_rgb_colorspace_image = 'lib/data/cmyk_to_rgb_colorspace.png';

  public $max_rgb_value = 0;

  /**************************************************************************************/

  public function __construct() {

    $this->init_values();

  } // __construct

  /**************************************************************************************/

  public function init_values() {

    // Init the max color value.
    $this->max_rgb_value = 255;

    // Init the RGB to CMY map array.
    $this->rgb_to_cmy_map['red'] = 'cyan';
    $this->rgb_to_cmy_map['green'] = 'magenta';
    $this->rgb_to_cmy_map['blue'] = 'yellow';

    // Init the RGB to shift map array.
    $this->rgb_to_shift_map['red'] = 16;
    $this->rgb_to_shift_map['green'] = 8;
    $this->rgb_to_shift_map['blue'] = 0;

    // Init the RGB to gray luma generic map array.
    $this->rgb_to_gray_luma_map['standard'] = array();
    $this->rgb_to_gray_luma_map['standard']['red'] = 1;
    $this->rgb_to_gray_luma_map['standard']['green'] = 1;
    $this->rgb_to_gray_luma_map['standard']['blue'] = 1;

    $this->rgb_to_gray_luma_map['generic'] = array();
    $this->rgb_to_gray_luma_map['generic']['red'] = 0.3086;
    $this->rgb_to_gray_luma_map['generic']['green'] = 0.6094;
    $this->rgb_to_gray_luma_map['generic']['blue'] = 0.0820;

    // Init the RGB to gray luma rec601 map array.
    $this->rgb_to_gray_luma_map['rec601'] = array();
    $this->rgb_to_gray_luma_map['rec601']['red'] = 0.2989;
    $this->rgb_to_gray_luma_map['rec601']['green'] = 0.5870;
    $this->rgb_to_gray_luma_map['rec601']['blue'] = 0.1140;

    // Init the RGB to gray luma rec709 map array.
    $this->rgb_to_gray_luma_map['rec709'] = array();
    $this->rgb_to_gray_luma_map['rec709']['red'] = 0.2126;
    $this->rgb_to_gray_luma_map['rec709']['green'] = 0.7152;
    $this->rgb_to_gray_luma_map['rec709']['blue'] = 0.0722;

    // Init the RGB component names.
    $this->rgb_components = array('red', 'green', 'blue');

    // Init the CMY component names.
    $this->cmy_components = array('cyan', 'magenta', 'yellow');

    // Init the CMYK component names.
    $this->cmyk_components = array('cyan', 'magenta', 'yellow', 'black');

    // Init the HSL component names.
    $this->hsl_components = array('hue', 'saturation', 'lightness');

    // Init the HSV component names.
    $this->hsv_components = array('hue', 'saturation', 'value');

  } // init_values

  /**************************************************************************************/

  public function rgb_to_hex ($rgb_value = array()) {

    return sprintf("#%02X%02X%02X", $rgb_value['red'], $rgb_value['green'], $rgb_value['blue']);

  } // rgb_to_hex

  /**************************************************************************************/

  public function rgb_to_cmyk ($rgb_value = array()) {

    return $this->cmy_to_cmyk($this->rgb_to_cmy($rgb_value));

  } // rgb_to_cmyk

  /**************************************************************************************/

  public function rgb_to_cmy ($rgb_value = array()) {

    // Init the basic values.
    $round_to = 6;

    // Roll through the RGB to CMY values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_to_cmy_map as $rgb_name => $cmy_name) {
      $ret[$cmy_name] = round((1 - ($rgb_value[$rgb_name] / $this->max_rgb_value)), $round_to) / 100;
    }

    return $ret;

  } // rgb_to_cmy

  /**************************************************************************************/

  public function rgb_to_hsl ($rgb_value = array()) {

    // Init the basic values.
    $round_to = 6;
    $hue = $saturation = $lightness = 0;

    // Calculate percentages for all three colors.
    foreach ($this->rgb_components as $rgb_component) {
      $$rgb_component = $rgb_value[$rgb_component] / $this->max_rgb_value;
    }

    // Get the max and min values of the RGB values.
    $max_rgb = max($red, $green, $blue);
    $min_rgb = min($red, $green, $blue);

    // Calculate lightness by adding max and min values and dividing by two.
    $lightness = ($max_rgb + $min_rgb) / 2;

    // Get the chroma which is the delta between max and min values.
    $chroma = $max_rgb - $min_rgb;

    // Calculate the hue and saturation.
    if ($chroma == 0) {

      // If the chroma is 0, the max_rgb and min_rgb are the same, so then the hue and saturation is 0.
      $hue = $saturation = 0;

    } // if
    else {

      // If the chroma is not 0, then we calculate the saturation like this.
      $saturation = $lightness > 0.5 ? ($chroma / (2 - $max_rgb - $min_rgb)) : ($chroma / ($max_rgb + $min_rgb));

      // Calculate the hue.
      switch($max_rgb) {
        case $red:
          $hue = fmod((($green - $blue) / $chroma), 6) * 60;
          if ($blue > $green) {
            $hue += 360;
          }
          break;
        case $green:
          $hue = (($blue - $red) / $chroma + 2) * 60;
          break;
        case $blue:
          $hue = (($red - $green) / $chroma + 4) * 60;
          break;
      }

    } // else

    // Round the final values and assign them to an array.
    $ret = array();
    $ret['hue'] = round($hue, $round_to);
    $ret['saturation'] = round($saturation, $round_to) * 100;
    $ret['lightness'] = round($lightness, $round_to) * 100;

    // Return the final values.
    return $ret;

  } // rgb_to_hsl

  /**************************************************************************************/

  public function rgb_to_hsv ($rgb_value = array()) {

    // Init the basic values.
    $round_to = 6;
    $hue = $saturation = $value = 0;

    // Calculate percentages for all three colors.
    foreach ($this->rgb_components as $rgb_component) {
      $$rgb_component = $rgb_value[$rgb_component] / $this->max_rgb_value;
    }

    // Get the max and min values of the RGB values.
    $max_rgb = max($red, $green, $blue);
    $min_rgb = min($red, $green, $blue);

    // Get the chroma which is the delta between max and min values.
    $chroma = $max_rgb - $min_rgb;

    // Calculate value.
    $value = $max_rgb;

    // If the chroma is 0, the max_rgb and min_rgb are the same, so then the hue and saturation is 0.
    if ($chroma == 0) {

      // If the chroma is 0, the max_rgb and min_rgb are the same, so then the hue and saturation is 0.
      $hue = $saturation = 0;

    } // if
    else {

      // If the chroma is not 0, then we calculate the saturation like this.
      $saturation = ($chroma / $max_rgb);

      // Calculate hue.
      if ($red == $min_rgb) {
        $hue = 3 - (($green - $blue) / $chroma);
      }
      elseif ($blue == $min_rgb) {
        $hue = 1 - (($red - $green) / $chroma);
      }
      else { // $green == $min_rgb
        $hue = 5 - (($blue - $red) / $chroma);
      }

    } // else

    // Round the final values and assign them to an array.
    $ret = array();
    $ret['hue'] = round($hue * 60, $round_to);
    $ret['saturation'] = round($saturation, $round_to) * 100;
    $ret['value'] = round($value, $round_to) * 100;

    // Return the final values.
    return $ret;

  } // rgb_to_hsv

  /**************************************************************************************/

  public function rgb_to_gray ($rgb_value = array(), $luma_type = 'standard') {

    $gray_array = array();
    foreach ($this->rgb_components as $rgb_name) {
      $gray_array[$rgb_name] = $rgb_value[$rgb_name] * $this->rgb_to_gray_luma_map[$luma_type][$rgb_name];
    }

    $gray = round(array_sum(array_values($gray_array)) / count($gray_array));

    // Roll through the RGB shift map values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_components as $rgb_name) {
      $ret[$rgb_name] = $gray;
    }

    return $ret;

  } // rgb_to_gray

  /**************************************************************************************/

  public function rgb_invert ($rgb_value = array()) {

    // Invert the color by subtracting the value from the max RGB value.
    foreach ($this->rgb_components as $rgb_name) {
      $$rgb_name = $this->max_rgb_value - $rgb_value[$rgb_name];
    }

    // Roll through the RGB shift map values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_components as $rgb_name) {
      $ret[$rgb_name] = $$rgb_name;
    }

    return $ret;

  } // rgb_invert

  /**************************************************************************************/

  public function gray_percentage ($gray_value = array()) {

    // Init the basic values.
    $round_to = 6;

    // Calculate the gray percentage.
    $ret = round((array_sum(array_values($gray_value)) / count($gray_value)) / $this->max_rgb_value, $round_to);

    // Return the final percentage.
    return round($ret * 100, $round_to);

  } // gray_percentage

  /**************************************************************************************/

  public function cmyk_to_rgb ($cmyk_value = array()) {

    // Calculate the X and Y coordinates on the image.
    $x_coor = round($cmyk_value['yellow'] / 5) * 21 + round($cmyk_value['cyan'] / 5);
    $y_coor = round($cmyk_value['black'] / 5) * 21 + round($cmyk_value['magenta'] / 5);

    // This is the image with CMYK to RGB color values.
    $cmyk_map = ImageCreateFromPng($this->cmyk_to_rgb_colorspace_image);

    // This 'eyedrops' the RGB value from the above table.
    $rgb_value = ImageColorAt($cmyk_map, $x_coor, $y_coor);

    // Roll through the RGB shift map values and assign accordingly.
    $ret = array();
    foreach ($this->rgb_to_shift_map as $rgb_name => $shift_value) {
      $ret[$rgb_name] = ($rgb_value >> $shift_value) & 0xFF;
    }

    return $ret;

  } // cmyk_to_rgb

  /**************************************************************************************/

  public function cmy_to_cmyk ($cmy_value = array()) {

    // Init the basic values.
    $round_to = 6;

    // Init the default black level to 1.
    $cmy_value['black'] = 1;

    // Roll through all of the CMY components to see if they are pure black (1) or not.
    foreach ($this->cmy_components as $cmy_component) {
      $cmy_value['black'] = ($cmy_value[$cmy_component] < $cmy_value['black']) ? $cmy_value[$cmy_component] : $cmy_value['black'];
    }

    // If the CMY black value is pure black do this.
    if ($cmy_value['black'] == 1) {
      foreach ($this->cmy_components as $cmy_component) {
        $cmy_value[$cmy_component] = 0;
      }
    }
    else {
      foreach ($this->cmy_components as $cmy_component) {
        $cmy_value[$cmy_component] = round(((($cmy_value[$cmy_component] - $cmy_value['black']) / (1 - $cmy_value['black']) * 100)), $round_to);
      }
    }

    // Set the final CMY black value.
    $cmy_value['black'] = round(($cmy_value['black'] * 100), $round_to);

    return $cmy_value;

  } // cmy_to_cmyk

  /**************************************************************************************/

  public function hsl_to_rgb ($hsl_value = array()) {

    // Init the basic values.
    $round_to = 2;
    $red = $green = $blue = 0;

    // Extract the HSL values.
    list($hue, $saturation, $lightness) = array_values($hsl_value);

    // Convert degrees and percentages back to decimals.
    $hue_degrees = $hue / 360;
    $saturation = $saturation / 100;
    $lightness = $lightness / 100;

    // Six sides in a hexagon HSL model, so multiply hue value by 6.
    $hue_degrees = $hue_degrees * 6;

    // Round to the floor of the value to determine what side of the hexagon the value is on.
    $hue_floor = floor($hue_degrees);

    // Set the chroma.
    $chroma = (1 - abs(2 * $lightness - 1)) * $saturation;

    // Set related temporary values.
    $temp_1 = $chroma * (1 - abs(fmod(($hue / 60), 2) - 1));
    $temp_2 = ($lightness - ($chroma / 2));

    // Assign the RGB values based on what side of the hexagon we are on.
    if ($hue_floor == 0) {
      $red = $chroma;
      $green = $temp_1;
      $blue = 0;
    }
    elseif ($hue_floor == 1) {
      $red = $temp_1;
      $green = $chroma;
      $blue = 0;
    }
    elseif ($hue_floor == 2) {
      $red = 0;
      $green = $chroma;
      $blue = $temp_1;
    }
    elseif ($hue_floor == 3) {
      $red = 0;
      $green = $temp_1;
      $blue = $chroma;
    }
    elseif ($hue_floor == 4) {
      $red = $temp_1;
      $green = 0;
      $blue = $chroma;
    }
    elseif ($hue_floor == 5) {
      $red = $chroma;
      $green = 0;
      $blue = $temp_1;
    }

    // Round the final values and assign them to an array.
    $ret = array();
    foreach ($this->rgb_components as $rgb_component) {
      $ret[$rgb_component] = round(($$rgb_component + $temp_2) * $this->max_rgb_value, $round_to);
    }

    // Return the final values.
    return $ret;

  } // hsl_to_rgb

  /**************************************************************************************/

  public function hsv_to_rgb ($hsv_value) {

    // Extract the HSV values.
    list($hue, $saturation, $value) = array_values($hsv_value);

    // Init the basic values.
    $round_to = 0;
    $red = $green = $blue = 0;

    // Convert degrees and percentages back to decimals.
    $hue = $hue / 360;
    $saturation = $saturation / 100;
    $value = $value / 100;

    // If saturation is 0, then the color is grey and that is all she wrote.
    if ($saturation == 0) {
      $red = $green = $blue = $value;
    }
    else {

      // Six sides in a hexagon HSV model, so multiply hue value by 6.
      $hue = $hue * 6;

      // Round to the floor of the value to determine what side of the hexagon the value is on.
      $hue_floor = floor($hue);

      // Calculate the temp values.
      $temp_1 = $value * (1 - $saturation);
      $temp_2 = $value * (1 - $saturation * ($hue - $hue_floor));
      $temp_3 = $value * (1 - $saturation * (1 - ($hue - $hue_floor)));

      // Assign the RGB values based on what side of the hexagon we are on.
      if ($hue_floor == 0) {
        $red = $value;
        $green = $temp_3;
        $blue = $temp_1;
      }
      elseif ($hue_floor == 1) {
        $red = $temp_2;
        $green = $value;
        $blue = $temp_1;
      }
      elseif ($hue_floor == 2) {
        $red = $temp_1;
        $green = $value;
        $blue = $temp_3;
      }
      elseif ($hue_floor == 3) {
        $red = $temp_1;
        $green = $temp_2;
        $blue = $value;
      }
      elseif ($hue_floor == 4) {
        $red = $temp_3;
        $green = $temp_1;
        $blue = $value;
      }
      elseif ($hue_floor == 5) {
        $red = $value;
        $green = $temp_1;
        $blue = $temp_2;
      }

    }

    // Round the final values and assign them to an array.
    $ret = array();
    foreach ($this->rgb_components as $rgb_component) {
      $ret[$rgb_component] = round($$rgb_component * $this->max_rgb_value, $round_to);
    }

    // Return the final values.
    return $ret;

  } // hsv_to_rgb

  /**************************************************************************************/

  public function hex_to_rgb ($hex_value) {

    // Convert the HEX value into an RGB array.
    $raw_rgb_array = array_map('hexdec', str_split($hex_value, 2));

    // Round the final values and assign them to an array.
    $ret = array();

    if (!empty($raw_rgb_array)) {
      foreach ($this->rgb_components as $rgb_key => $rgb_component) {
        $ret[$rgb_component] = $raw_rgb_array[$rgb_key];
      }
    }

    // Return the final values.
    return $ret;

  } // rgb_to_hex

  /**************************************************************************************/

  public function pms_to_rgb ($pms_value) {

    // Round the final values and assign them to an array.
    $ret = array();

    // Get the PMS to RGB map loaded.
    $pms_data = $this->read_pms_data();

    // Simple validation.
    if (array_key_exists($pms_value, $pms_data)) {
      $ret = $pms_data[$pms_value];
    }
    else {
      foreach ($this->rgb_components as $rgb_key => $rgb_component) {
        $ret[$rgb_component] = 00;
      }
    }

    // Return the final values.
    return $ret;

  } // pms_to_rgb



} // Conversions

?>