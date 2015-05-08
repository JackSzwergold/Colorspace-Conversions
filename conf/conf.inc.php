<?php

/**
 * Config File (config.inc.php) (c) by Jack Szwergold
 *
 * Config File is licensed under a
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

//**************************************************************************************************/
// Include the local settings.

require_once 'local.inc.php';

/**************************************************************************************************/
// Define the remaining basics.

// Define BASE_FILEPATH
$script_filename_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
define('BASE_FILEPATH', $script_filename_parts['dirname']);

// Detect if protocol is 'http' or 'https'
$URL_PROTOCOL = (array_key_exists('HTTPS', $_SERVER) && 'on' == $_SERVER['HTTPS'])
                || $_SERVER['SERVER_PORT'] == '443'
                ? 'https' : 'http';

// Detect ports used by $URL_PROTOCOL
if (('http' == $URL_PROTOCOL && '80' == $_SERVER['SERVER_PORT'])
    || ('https' == $URL_PROTOCOL && '443' == $_SERVER['SERVER_PORT']))
    $URL_PORT = '';
else
    $URL_PORT = ':' . $_SERVER['SERVER_PORT'];

$URL_HOST = $URL_PROTOCOL . '://' . $_SERVER['SERVER_NAME'] . $URL_PORT;

// Define BASE_URL
define('BASE_URL', $URL_HOST . BASE_PATH);

/**************************************************************************************************/
// Define the defaults.

$VALID_CONTENT_TYPES = array('application/json','text/plain','text/html');
$VALID_CHARSETS = array('utf-8','iso-8859-1','cp-1252');

?>
