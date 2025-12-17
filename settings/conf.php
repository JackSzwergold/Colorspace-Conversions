<?php

/******************************************************************************/
//   ____             __ _
//  / ___|___  _ __  / _(_) __ _
// | |   / _ \| '_ \| |_| |/ _` |
// | |__| (_) | | | |  _| | (_| |
//  \____\___/|_| |_|_| |_|\__, |
//                         |___/
//
// The core, application specific config stuff.
/******************************************************************************/

/******************************************************************************/
// Load the local config and bootstrap items.
require_once('bootstrap.php');
require_once('local.php');

/******************************************************************************/
// TODO: Generate a nonce if we are using metatag based Content-Security-Policy.
// $NONCE = base64_encode(random_bytes(20));
$NONCE = bin2hex(openssl_random_pseudo_bytes(32));

/******************************************************************************/
// Set the HTML templating options.
$TEMPLATE_FRAMEWORK = 'bootstrap-5.3';

/**************************************************************************************************/
// Site descriptive info.
$SITE_DEFAULT_CONTROLLER = 'small';

/**************************************************************************************************/
// Set the controller and parameter stuff.
$VALID_CONTROLLERS = array('parent', 'child', 'grandchild', 'greatgrandchild');
$DISPLAY_CONTROLLERS = array('parent');
$VALID_GET_PARAMETERS = array('parent', 'child', 'grandchild', 'greatgrandchild', '_debug', 'json', 'offset', 'count');

?>
