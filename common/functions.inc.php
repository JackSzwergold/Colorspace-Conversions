<?php

/**
 * Functions (functions.inc.php) (c) by Jack Szwergold
 *
 * Functions is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>. 
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2014-01-22, js
 * Version: 2014-01-22, js: creation
 *          2014-01-22, js: development & cleanup
 *
 */

// Nice PHP JSON formatting function.
// SOURCE: http://stackoverflow.com/questions/6054033/pretty-printing-json-with-php/9776726#9776726
function prettyPrint ($json) {

  $result = '';
  $level = 0;
  $prev_char = '';
  $in_quotes = false;
  $ends_line_level = NULL;
  $json_length = strlen($json);

  for ($i = 0; $i < $json_length; $i++) {
    $char = $json[$i];
    $new_line_level = NULL;
    $post = "";
    if ($ends_line_level !== NULL) {
      $new_line_level = $ends_line_level;
      $ends_line_level = NULL;
    }
    if ($char === '"' && $prev_char != '\\') {
      $in_quotes = !$in_quotes;
    }
    elseif (!$in_quotes) {
      switch( $char ) {
        case '}': case ']':
          $level--;
          $ends_line_level = NULL;
          $new_line_level = $level;
          break;
        case '{': case '[':
          $level++;
        case ',':
          $ends_line_level = $level;
          break;
        case ':':
          $post = " ";
          break;
        case " ": case "\t": case "\n": case "\r":
          $char = "";
          $ends_line_level = $new_line_level;
          $new_line_level = NULL;
          break;
        }
      }
      if ($new_line_level !== NULL) {
        $result .= "\n".str_repeat( "\t", $new_line_level );
      }
      $result .= $char.$post;
      $prev_char = $char;
  }
  return $result;

} // prettyPrint

?>
