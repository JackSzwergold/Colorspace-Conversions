<?php

/**
 * Colorspace Display Stuff (colorspace_display.class.php) (c) by Jack Szwergold
 *
 * Colorspace Display Stuff is licensed under a
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
 *          2015-05-01, js: adding RGB to HSL conversion test stuff.
 *          2015-05-02, js: adding HSL to RGB conversion test stuff.
 *          2015-05-05, js: calculating gray to get nicer, contrast on colors.
 *
 */

//**************************************************************************************//
// Require (once) the parent helpers class.
require_once('colorspace_helpers.class.php');

//**************************************************************************************//
// Here is where the magic happens!
class Display extends Helpers {

  public $rgb = NULL;
  public $gray = NULL;
  public $gray_percentage = 0;

  public $gray_text_cutoff = 36;

  public $hex = NULL;
  public $hex_inverted = NULL;
  public $hex_gray = NULL;
  public $hex_gray_inverted = NULL;

  public $show_rgb_grid = false;
  public $show_cmyk_grid = false;
  public $show_pms_grid = false;

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
    // Return the final return values.
    return $ret;

  } // init

  /**************************************************************************************/
  // The set infobox content method.
  private function set_infobox_content($final = array()) {

    /************************************************************************************/
    // Init the basics.
    $ret = null;

    /************************************************************************************/
    // Set the hex infobox.
    $infobox = null;
    if (isset($this->hex)) {

      /**********************************************************************************/
      // Set the text hex color based on the gray percentage.
      $css = $this->gray_percentage > $this->gray_text_cutoff ? 'text-black' : 'text-white';

      /**********************************************************************************/
      // Build the infobox.
      $infobox .= sprintf('<div class="InfoBox col col-12 m-0 p-0 px-2 py-1 %s" style="background-color: %s">', $css, $this->hex);
      foreach ($final as $key => $value) {
        $infobox .= sprintf('<p class="m-0 p-0 text"><b>%s</b>: %s</p>', strtoupper($key), $value);
      }
      $infobox .= '</div><!-- .InfoBox -->';

    } // if

    /************************************************************************************/
    // RGB grid.
    $rgb_grid = $this->rgb_grid();

    /************************************************************************************/
    // CMYK grid.
    // $cmyk_grid = $this->cmyk_grid();

    /************************************************************************************/
    // PMS grid.
    $pms_grid = $this->pms_grid();

    /************************************************************************************/
    // Set the final return value.
    $ret =
        $infobox
      . '<div class="RGB col col-12">'
      . $rgb_grid
      . '</div><!-- .RGB -->'
      // . '<div class="CMYK col col-12">'
      // . $cmyk_grid
      // . '</div><!-- .CMYK -->'
      . '<div class="PMS col col-12">'
      . $pms_grid
      . '</div><!-- .PMS -->'
      ;

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
  private function build_pixel_box($url = null, $hex = null, $text = null, $css = null) {

    /************************************************************************************/
    // Do something.
    $ret =
        sprintf('<a href="%s">', $url)
      . sprintf('<span class="PixelBox d-inline-block m-0 p-0 %s" style="background-color: %s;">', $css, $hex)
      . (!empty($text) ? sprintf('<p class="m-0 p-0 px-2 py-1"><small>%s</small></p>', $text) : null)
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

} // Display

?>
