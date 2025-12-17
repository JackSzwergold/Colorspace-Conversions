<?php

/******************************************************************************/
//  ____              _       _
// | __ )  ___   ___ | |_ ___| |_ _ __ __ _ _ __
// |  _ \ / _ \ / _ \| __/ __| __| '__/ _` | '_ \
// | |_) | (_) | (_) | |_\__ \ |_| | | (_| | |_) |
// |____/ \___/ \___/ \__|___/\__|_|  \__,_| .__/
//                                         |_|
//
// Base bootstrapping logic for the config. Sets core variables and constants.
/******************************************************************************/

/******************************************************************************/
// Set the 'BASE_FILEPATH' value.
$script_filename_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
define('BASE_FILEPATH', $script_filename_parts['dirname']);

/******************************************************************************/
// Set the 'BASE_PATH' value.
$dirname_parts = preg_split("/\//", $script_filename_parts['dirname']);
$document_root_parts = preg_split("/\//", $_SERVER['DOCUMENT_ROOT']);
$url_path_parts = array_diff(array_values(array_filter($dirname_parts)) , array_values(array_filter($document_root_parts)) );
$final_path_array = array_values(array_filter($url_path_parts));
if (count($final_path_array) >= 1) {
  define('BASE_PATH', '/' . implode('/', $final_path_array ) . '/');
} // if
else {
  define('BASE_PATH', '/');
} // else

/******************************************************************************/
// Set the 'URL_PROTOCOL' value.
$URL_PROTOCOL = 'http';
if ((array_key_exists('HTTPS', $_SERVER) && 'on' == $_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == '443') {
  $URL_PROTOCOL = 'https';
} // if

/******************************************************************************/
// Force a different 'URL_PROTOCOL' value if a local override is set.
if (isset($URL_PROTOCOL_LOCAL) && !empty($URL_PROTOCOL_LOCAL)) {
  $URL_PROTOCOL = $URL_PROTOCOL_LOCAL;
} // if

/******************************************************************************/
// Set the 'URL_PORT' value.
$URL_PORT = ':' . $_SERVER['SERVER_PORT'];
if (('http' == $URL_PROTOCOL && '80' == $_SERVER['SERVER_PORT']) || ('https' == $URL_PROTOCOL && '443' == $_SERVER['SERVER_PORT'])) {
  $URL_PORT = '';
} // if

/******************************************************************************/
// Set the 'URL_HOST' value.
$URL_HOST = $URL_PROTOCOL . '://' . $_SERVER['SERVER_NAME'] . $URL_PORT;

/******************************************************************************/
// Define 'BASE_URL' value.
if (isset($BASE_URL_LOCAL) && !empty($BASE_URL_LOCAL)) {
  define('BASE_URL', $BASE_URL_LOCAL);
} // if
else {
  define('BASE_URL', $URL_HOST . BASE_PATH);
} // else

/******************************************************************************/
// Define 'BASE_URI' value.
$BASE_URI = null;
if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
  // $BASE_URI = str_replace(BASE_PATH, null, $_SERVER['REQUEST_URI']);
  $uri_parts = explode(BASE_PATH, $_SERVER['REQUEST_URI'], 2);
  $uri_parts = array_filter($uri_parts);
  $BASE_URI = array_shift($uri_parts);
} // if
else if (isset($_SERVER['REDIRECT_URL']) && !empty($_SERVER['REDIRECT_URL'])) {
  // $BASE_URI = str_replace(BASE_PATH, null, $_SERVER['REDIRECT_URL']);
  $uri_parts = explode(BASE_PATH, $_SERVER['REDIRECT_URL'], 2);
  $uri_parts = array_filter($uri_parts);
  $BASE_URI = array_shift($uri_parts);
} // else if

/******************************************************************************/
// Set the BASE_URL based only on the query parameters.
$parsed_base_uri = array();
if (!empty($BASE_URI)) {
  $parsed_base_uri = parse_url($BASE_URI);
} // if
$base_uri_path = null;
if (isset($parsed_base_uri['path']) && !empty($parsed_base_uri['path'])) {
  $base_uri_path = $parsed_base_uri['path'];
} // if
define('BASE_URI', $base_uri_path);

/******************************************************************************/
// Define the 'VALID_CONTENT_TYPES' and 'VALID_CHARSETS' values.
$VALID_CONTENT_TYPES = array('text/html', 'text/plain', 'application/json', 'application/vnd.api+json', 'text/csv');
$VALID_CHARSETS = array('utf-8', 'iso-8859-1', 'cp-1252');

?>
