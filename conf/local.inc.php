<?php

/**
 * Local Config File (local.inc.php) (c) by Jack Szwergold
 *
 * Local Config File is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>. 
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2014-02-16, js
 * Version: 2014-02-16, js: creation
 *          2014-02-16, js: development & cleanup
 *
 */

if ($_SERVER['SERVER_NAME'] == 'localhost') {
  define('BASE_PATH', '/Colorspace-Conversions/');
}
else {
  define('BASE_PATH', '/colorspace/');
}

/**************************************************************************************************/
// Define localized defaults.

$VALID_CONTROLLERS = array('colorspace', 'value');
$DISPLAY_CONTROLLERS = array('colorspace', 'value');

$VALID_GET_PARAMETERS = array('_debug', 'colorspace', 'value');

?>
