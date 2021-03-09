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
 * w: http://www.preworn.com
 * e: me@preworn.com
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

  public $gray_text_cutoff = 32;

  public $text_class_light = 'light';
  public $text_class_dark = 'dark';

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

  public function init ($colorspace = NULL, $value = NULL) {

    $rgb_array = array();

    if ($colorspace == 'rgb') {
      $rgb_array = $this->get_rgb_values($value);
    }
    elseif ($colorspace == 'cmyk') {
      $cmyk_array = $this->get_cmyk_values($value);
      $rgb_array = $this->cmyk_to_rgb($cmyk_array);
    }
    elseif ($colorspace == 'hex') {
      $hex_array = $this->get_hex_values($value);
      $rgb_array = $this->hex_to_rgb($hex_array);
    }
    elseif ($colorspace == 'pms') {
      $pms_value = $this->get_pms_values($value);
      $rgb_array = $this->pms_to_rgb($pms_value);
    }

    $final_values = $this->get_color_values($rgb_array);

    return $this->set_body_content($final_values);

  } // init

  /**************************************************************************************/

  public function get_rgb_values ($rgb_get = NULL) {

    // Init the basics.
    $rgb_array = array();

    // Get the RGB component names from the passed $_GET string.
    list($rgb_array['red'], $rgb_array['green'], $rgb_array['blue']) = explode('_', $rgb_get);

    // Loop through the RGB components.
    foreach ($this->rgb_components as $rgb_component) {
      $rgb_array[$rgb_component] = intval($rgb_array[$rgb_component]) > 255 ? 255 : $rgb_array[$rgb_component];
      $rgb_array[$rgb_component] = intval($rgb_array[$rgb_component]) < 0 ? 0 : $rgb_array[$rgb_component];
    } // foreach

    return $rgb_array;

  } // get_rgb_values

  /**************************************************************************************/

  public function get_cmyk_values ($cmyk_get = NULL) {

    // Init the basics.
    $cmyk_array = array();

    // Get the CMYK component names from the passed $_GET string.
    list($cmyk_array['cyan'], $cmyk_array['magenta'], $cmyk_array['yellow'], $cmyk_array['black']) = explode('_', $cmyk_get);

    // Loop through the CMYK components.
    foreach ($this->cmyk_components as $cmyk_component) {
      $cmyk_array[$cmyk_component] = intval($cmyk_array[$cmyk_component]) > 100 ? 100 : $cmyk_array[$cmyk_component];
      $cmyk_array[$cmyk_component] = intval($cmyk_array[$cmyk_component]) < 0 ? 0 : $cmyk_array[$cmyk_component];
    } // foreach

    return $cmyk_array;

  } // get_cmyk_values

  /**************************************************************************************/

  public function get_hex_values ($hex_get = NULL) {

    // Init the basics.
    $ret = '';

    // Check if the hex is valid.
    if (!empty($hex_get) && ctype_xdigit($hex_get)){
      $ret = $hex_get;
    }
    else {
      $ret = '000000';
    }

    return $ret;

  } // get_hex_values

  /**************************************************************************************/

  public function get_pms_values ($pms_get = NULL) {

    // Init the basics.
    $ret = '';

    // Check if the hex is valid.
    if (!empty($pms_get)){
      $ret = $pms_get;
    }

    return $ret;

  } // get_pms_values

  /**************************************************************************************/

  public function get_color_values ($rgb_array = array()) {

    // Sanitize the RGB array.
    $rgb_array = empty($rgb_array) ? array('red' => 0, 'green' => 0, 'blue' => 0) :  $rgb_array;

    // Convert the RGB value to gray.
    $this->gray = $this->rgb_to_gray($rgb_array, 'standard');

    // Convert the gray value to a percentage.
    $this->gray_percentage = $this->gray_percentage($this->gray);

    // Convert the RGB value to hexadecimal.
    $this->hex = $this->rgb_to_hex($rgb_array);

    // Invert the RGB and set it as a hexadecimal value for layout purposes.
    $this->hex_inverted = $this->rgb_to_hex($this->rgb_invert($rgb_array));

    // Covert the RGB to gray and set it as a hexadecimal value for layout purposes.
    $this->hex_gray = $this->rgb_to_hex($this->gray);

    // Invert the RGB to gray and set it as a hexadecimal value for layout purposes.
    $this->hex_gray_inverted = $this->rgb_to_hex($this->rgb_invert($this->gray));

    // Convert the RGB value to CMYK.
    $cmyk_array = $this->rgb_to_cmyk($rgb_array);

    // Convert the RGB value to HSL.
    $hsl = $this->rgb_to_hsl($rgb_array);

    // Convert the HSL value to RGB.
    $hsl_back_to_rgb = $this->hsl_to_rgb($hsl);

    // Convert the RGB value to HSV.
    $hsv = $this->rgb_to_hsv($rgb_array);

    // Convert the HSV value to RGB.
    $hsv_back_to_rgb = $this->hsv_to_rgb($hsv);

    // Set all of the different values.
    $ret = array();
    $ret['hex'] = sprintf('<a href="hex/%s">%s</a>', ltrim($this->hex, '#'), $this->hex);
    $ret['hex_inverted'] = sprintf('<a href="hex/%s">%s</a>', ltrim($this->hex_inverted, '#'), $this->hex_inverted);
    $ret['hex_gray'] = sprintf('<a href="hex/%s">%s</a>', ltrim($this->hex_gray, '#'), $this->hex_gray);
    $ret['hex_gray_inverted'] = sprintf('<a href="hex/%s">%s</a>', ltrim($this->hex_gray_inverted, '#'), $this->hex_gray_inverted);
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

  public function build_url ($params) {

    return implode('/', $params);

  } // build_url

  /**************************************************************************************/

  public function build_pixel_box ($url, $hex, $text, $css = null) {

    $ret = sprintf('<a href="%s">', $url)
         . sprintf('<div class="PixelBox %s" style="background-color: %s;">', $css, $hex)
         . '<div class="Padding">'
         . sprintf('<p>%s</p>', $text)
         . '</div><!-- .Padding -->'
         . '</div><!-- .PixelBox -->'
         . '</a>'
         ;

    return $ret;

  } // build_pixel_box

  /**************************************************************************************/

  public function rgb_grid () {

    $ret = '';

    $span = array_fill(1, $this->rgb_span, NULL);
    $step = range(1, $this->rgb_span, $this->rgb_step);

    $rgb_test = array('red' => $step, 'green' => $step, 'blue' => $step);

    if (FALSE) {
      rsort($rgb_test['red']);
      rsort($rgb_test['green']);
      rsort($rgb_test['blue']);
    }

    foreach ($rgb_test['red'] as $red) {
      foreach ($rgb_test['green'] as $green) {
        foreach ($rgb_test['blue'] as $blue) {
          $color = array('red' => $red, 'green' => $green, 'blue' => $blue);
          $hex = $this->rgb_to_hex($color);
          $rgb = sprintf('%s_%s_%s', $red, $green, $blue);
          $url = $this->build_url(array('colorspace' => 'rgb', 'value' => $rgb));
          $text = '<!-- -->';
          $ret .= $this->build_pixel_box($url, $hex, $text);
        }
      }
    }

    return $ret;

  } // rgb_grid

  /**************************************************************************************/

  public function cmyk_grid () {

    $ret = '';

    $cmky_span = array_fill(1, $this->cmyk_span, NULL);
    $cmky_step = range(1, $this->cmyk_span, $cmky_span);

    for ($black = 0; $black <= ($this->cmyk_span - 20); $black += $this->cmyk_step) {
      for ($magenta = 0; $magenta <= $this->cmyk_span; $magenta += $this->cmyk_step) {
        for ($yellow = 0; $yellow <= $this->cmyk_span; $yellow += $this->cmyk_step) {
          for ($cyan = 0; $cyan <= $this->cmyk_span; $cyan += $this->cmyk_step) {
            $color = $this->cmyk_to_rgb(array('cyan' => $cyan, 'magenta' => $magenta, 'yellow' => $yellow, 'black' => $black));
            $hex = $this->rgb_to_hex($color);
            $cmyk = sprintf('%s_%s_%s_%s', $cyan, $magenta, $yellow, $black);
            $url = $this->build_url(array('colorspace' => 'cmyk', 'value' => $cmyk));
            $text = '<!-- -->';
            $ret .= $this->build_pixel_box($url, $hex, $text);
          }
        }
      }
    }

    return $ret;

  } // cmyk_grid

  /**************************************************************************************/

  public function pms_grid () {

    $ret = '';

    // Get the PMS data.
    $pms_data = $this->read_pms_data();

    if (!empty($pms_data)) {

      // Sort the PMS to hex array.
      ksort($pms_data);

      foreach ($pms_data as $pms_key => $pms_value) {

        // Set the CSS based on the gray percentage.
        $css =  $pms_value['gray_percentage'] > $this->gray_text_cutoff ? $this->text_class_dark : $this->text_class_light;

        // Set the RGB URL param.
        $rgb_param = sprintf('%s_%s_%s', $pms_value['red'], $pms_value['green'], $pms_value['blue']);

        // Set the URL.
        // $url = $this->build_url(array('colorspace' => 'rgb', 'value' => $rgb_param));
        $url = $this->build_url(array('colorspace' => 'pms', 'value' => $pms_key));

        // Set the text to be passed back into the pixel box.
        $pixel_text = sprintf('PMS %s', ucwords(preg_replace('~_+~', ' ', $pms_key)));

        // Set the pixel box.
        $ret .= $this->build_pixel_box($url, $pms_value['hex'], $pixel_text, $css);

      } // foreach

    }

    return $ret;

  } // pms_grid

  /**************************************************************************************/

  public function set_body_content ($final) {

    $ret = '<div class="InfoBox">'
         . '<div class="Padding">'
         . '<p><b>CMYK URL Format:</b> /cmyk/ccc_mmm_yyy_kkk (100_100_100_0)</p>'
         . '<p><b>RGB URL Format:</b> /rgb/rrr_ggg_bbb (255_255_255)</p>'
         . '<p><b>HEX URL Format:</b> /hex/hhhhhh (000000)</p>'
         . '<p><b>PMS URL Format:</b> /pms/xxxxxx (000_ABC)</p>'
         . '</div><!-- .Padding -->'
         . '</div><!-- .InfoBox -->'
         ;

    if (isset($this->hex)) {

      // Set the text hex color based on the gray percentage.
      $css =  $this->gray_percentage > $this->gray_text_cutoff ? $this->text_class_dark : $this->text_class_light;

      $ret .= sprintf('<div class="InfoBox %s" style="background-color: %s">', $css, $this->hex)
            . '<div class="Padding">'
            ;
      foreach ($final as $key => $value) {
        $ret .= sprintf('<p><b>%s</b>: %s</p>', strtoupper($key), $value);
      }
      $ret .= '</div><!-- .Padding -->'
            . '</div><!-- .InfoBox -->'
            ;
    }

    // RGB grid.
    if ($this->show_rgb_grid) {
      $ret .= '<div class="RGB">'
            . '<div class="Grid">'
            . '<div class="Padding">'
            . $this->rgb_grid()
            . '</div><!-- .Padding -->'
            . '</div><!-- .Grid -->'
            . '</div><!-- .RGB -->'
            ;
    }

    // CMYK grid.
    if ($this->show_cmyk_grid) {
      $ret .= '<div class="CMYK">'
            . '<div class="Grid">'
            . '<div class="Padding">'
            . $this->cmyk_grid()
            . '</div><!-- .Padding -->'
            . '</div><!-- .Grid -->'
            . '</div><!-- .CMYK -->'
            ;
    }

    // PMS grid.
    if ($this->show_pms_grid) {
      $ret .= '<div class="PMS">'
            . '<div class="Grid">'
            . '<div class="Padding">'
            . $this->pms_grid()
            . '</div><!-- .Padding -->'
            . '</div><!-- .Grid -->'
            . '</div><!-- .PMS -->'
            ;
    }

    return '<div class="PixelBoxContainer">'
         . '<div class="Padding">'
         . $ret
         . '</div><!-- .Padding -->'
         . '</div><!-- .PixelBoxContainer -->'
         ;

  } // set_body_content

} // Grids

?>
