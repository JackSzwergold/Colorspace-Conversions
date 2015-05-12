<?php

/**
 * Frontend Display Class (frontendDisplay.class.php) (c) by Jack Szwergold
 *
 * Frontend Display Class is licensed under a
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
 *          2014-01-23, js: refinements
 *          2014-02-17, js: setting a 'base'
 *          2014-02-27, js: adding a page URL
 *          2015-05-10, js: adding DIV wrapper class & id
 *          2015-05-11, js: setting dynamic DIV wrapper creation
 *
 */

//**************************************************************************************//
// The beginnings of a frontend display class.

class frontendDisplay {

  private $DEBUG_MODE = FALSE;

  private $content_type = 'text/html';
  private $charset = 'utf-8';
  private $doctype = 'html5';

  private $json_encode = FALSE;
  private $json_via_header = FALSE;

  private $params = array();

  private $javascripts = array();

  private $base = NULL;

  private $view_mode = NULL;
  private $page_url = NULL;
  private $page_copyright = NULL;
  private $page_title = NULL;
  private $page_description = NULL;
  private $page_content = NULL;

  private $page_div_wrapper_class = NULL;
  private $page_div_wrapper_id = NULL;
  private $page_div_wrappper_array = array();

  private $page_viewport = '';
  private $page_robots = '';

  private $page_markdown_file = NULL;

  public function __construct($content_type = NULL, $charset = NULL, $json_encode = NULL, $DEBUG_MODE = NULL) {
    global $VALID_CONTENT_TYPES, $VALID_CHARSETS;

    if (!defined('BASE_PATH')) {
      define('BASE_PATH', '/');
    }

    if (!defined('BASE_URL')) {
      define('BASE_URL', '');
    }

    if (!empty($content_type) && in_array($content_type, $VALID_CONTENT_TYPES)) {
      $this->content_type = $content_type;
    }

    if (!empty($charset) && in_array(strtolower($charset), $VALID_CHARSETS)) {
      $this->charset = $charset;
    }

    if (!empty($json_encode)) {
      $this->json_encode = $json_encode;
    }

    // if (!empty($json_via_header)) {
    //   $this->json_via_header = $json_via_header;
    // }

    if (!empty($DEBUG_MODE)) {
      $this->DEBUG_MODE = $DEBUG_MODE;
    }

  } // __construct


  //**************************************************************************************//
  // Set the page mode.
  function setViewMode($view_mode = null) {
    $this->view_mode = $view_mode;
  } // setViewMode


  //**************************************************************************************//
  // Set the page URL.
  function setPageURL($page_url = null) {
    $this->page_url = $page_url;
  } // setPageURL


  //**************************************************************************************//
  // Set the page Copyright.
  function setPageCopyright($page_copyright = null) {
    $this->page_copyright = $page_copyright;
  } // setPageCopyright


  //**************************************************************************************//
  // Set the page title.
  function setPageTitle($page_title = null) {
    $this->page_title = $page_title;
  } // setPageTitle


  //**************************************************************************************//
  // Set the page description.
  function setPageDescription($page_description = null) {
    $this->page_description = $page_description;
  } // setPageDescription


  //**************************************************************************************//
  // Set the page content markdown file.
  function setPageContentMarkdown($md_file = null) {
    $this->page_markdown_file = $md_file;
  } // setPageContentMarkdown


  //**************************************************************************************//
  // Set the page content.
  function setPageContent($content = null) {
    $this->content = $content;
  } // setPageContent


  //**************************************************************************************//
  // Set the page DIVs.
  function setPageDivs($page_div_wrappper_array = array()) {
    $this->page_div_wrappper_array = $page_div_wrappper_array;
  } // setPageDivs


  //**************************************************************************************//
  // Set the page DIV wrapper.
  function setPageDivWrapper($page_div_wrapper_class = null, $page_div_wrapper_id = null) {
    $this->page_div_wrapper_class = $page_div_wrapper_class;
    $this->page_div_wrapper_id = $page_div_wrapper_id;
  } // setPageDivWrapper


  //**************************************************************************************//
  // Set the page viewport.
  function setPageViewport($page_viewport = null) {
    $this->page_viewport = $page_viewport;
  } // setPageViewport


  //**************************************************************************************//
  // Set the page robots.
  function setPageRobots($page_robots = null) {
    $this->page_robots = $page_robots;
  } // setPageRobots


  //**************************************************************************************//
  // Set the additional javascripts.
  function setJavascripts($javascripts = null) {
    $this->javascripts = $javascripts;
  } // setJavascripts


  //**************************************************************************************//
  // Set the HTML base.
  function setPageBase($page_base = null) {
    $this->base = $page_base;
  } // setPageBase


  //**************************************************************************************//
  // Init the content.
  function initContent($response_header = NULL) {
    global $VALID_CONTROLLERS;

    //**************************************************************************************//
    // Filtrer the URL parameters

    $this->filterURLParameters();

    //**************************************************************************************//
    // Load the markdown content.

    $content = '';
    if (!empty($this->content)) {
      $content = $this->content;
    }
    else if (!empty($this->page_markdown_file)) {
      $content = $this->loadMarkdown($this->page_markdown_file);
    }

    //**********************************************************************************//
    // If the content is not empty, do something with it.

    if (!empty($content)) {

      //**********************************************************************************//
      // Set the meta tags

      $meta_content = $this->setMetaTags($this->page_description, $this->page_viewport, $this->page_robots);

      //**********************************************************************************//
      // Set the favicons

      $favicons = $this->setFavicons();

      //**********************************************************************************//
      // Set the HTML/XHTML doctype.

      $doctype = $this->setDoctype();


      //**********************************************************************************//
      // Set the JavaScript.

      $javascript = $this->setJavaScript();


      //**********************************************************************************//
      // Set the view wrapper.

      $body = sprintf('<div class="%sView">', $this->view_mode)
            . $this->setWrapper($content)
            . sprintf('</div><!-- .%sView -->', $this->view_mode)
            ;

       //**********************************************************************************//
      // Set the final content.

      $ret = $doctype
           . '<head>'
           . '<title>' . $this->page_title . '</title>'
           . join('', $meta_content)
           . '<link rel="stylesheet" href="' . BASE_URL . 'css/style.css" type="text/css" />'
           . join('', $favicons)
           . join('', $javascript)
           . (!empty($this->base) ? '<base href="' . $this->base . '" />' : '')
           . '</head>'
           . '<body>'
           . $body
           . '</body>'
           . '</html>'
           ;

      //**********************************************************************************//
      // Return the output.

      $this->renderContent($ret, $response_header);

    }

  } // initContent


  //**************************************************************************************//
  // Set the doctype.
  function setDoctype() {

    $ret = '';

    if ($this->doctype == 'xhtml') {
      $ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
           . '<html xmlns="http://www.w3.org/1999/xhtml">'
           ;
    }
    else if ($this->doctype == 'html5') {
      $ret = '<!DOCTYPE html>'
           . '<html lang="en">'
           ;
    }

    return $ret;

   } // setDoctype


  //**************************************************************************************//
  // Set the JavaScript.
  function setJavaScript() {

    // Set the javascript values.
    $javascripts = array();
    $javascripts[] = BASE_URL . 'script/json2.js';
    $javascripts[] = BASE_URL . 'script/jquery/jquery-1.10.2.min.js';
    $javascripts[] = BASE_URL . 'script/jquery/jquery-1.10.2.min.map';
    $javascripts[] = BASE_URL . 'script/jquery/jquery.noconflict.js';

    // Merge the base JavaScripts with the passed array of javasccripts.
    $javascripts = array_merge($javascripts, $this->javascripts);

    // Roll through the '$javascripts'
    $ret = array();
    foreach($javascripts as $javascript) {
      $ret[] = sprintf('<script src="%s" type="%s"></script>', $javascript, 'text/javascript');
    }

    return $ret;

  } // setJavaScript


  //**************************************************************************************//
  // Set the favicons.
  function setFavicons() {

    $favicons = array();

    $favicons['standard']['rel'] = 'icon';
    $favicons['standard']['type'] = 'image/png';
    $favicons['standard']['href'] = 'favicons/favicon.ico';

    $favicons['opera']['rel'] = 'icon';
    $favicons['opera']['type'] = 'image/png';
    $favicons['opera']['href'] = 'favicons/speeddial-160px.png';

    $favicons['iphone']['rel'] = 'apple-touch-icon-precomposed';
    $favicons['iphone']['href'] = 'favicons/apple-touch-icon-57x57-precomposed.png';

    $favicons['iphone4_retina']['rel'] = 'apple-touch-icon-precomposed';
    $favicons['iphone4_retina']['sizes'] = '114x114';
    $favicons['iphone4_retina']['href'] = 'favicons/apple-touch-icon-114x114-precomposed.png';

    $favicons['ipad']['rel'] = 'apple-touch-icon-precomposed';
    $favicons['ipad']['sizes'] = '72x72';
    $favicons['ipad']['href'] = 'favicons/apple-touch-icon-72x72-precomposed.png';

    $ret = array();
    foreach ($favicons as $favicon_type => $favicon_parts) {
      $parts = array();
      foreach ($favicon_parts as $favicon_key => $favicon_value) {
        $parts[] = $favicon_key . '="' .$favicon_value . '"';
      }
      $ret[$favicon_type] = sprintf('<!-- %s favicon -->', $favicon_type);
      $ret[$favicon_type] .= sprintf('<link %s />', join(' ', $parts));
    }

    return $ret;

  } // setFavicons


  //**************************************************************************************//
  // Set the meta content.

  function setMetaTags($description = null, $viewport = null, $robots = null) {

    // Set the meta property values.
    $meta_http_equivs = array();
    $meta_http_equivs['content-type'] = 'text/html; charset=utf-8';
    // $meta_http_equivs['imagetoolbar'] = 'no';

    // Set the meta property values.
    $meta_names = array();
    if (!empty($description)) {
      $meta_names['description'] = $description;
    }
    if (!empty($viewport)) {
      $meta_names['viewport'] = $viewport;
    }
    if (!empty($robots)) {
      $meta_names['robots'] = $robots;
    }

    // The copyright changes between 'xhtml' & 'html5'
    $copyright_key = '';
    if ($this->doctype == 'xhtml') {
      $copyright_key = 'copyright';
    }
    else if ($this->doctype == 'html5') {
      $copyright_key = 'dcterms.rightsHolder';
    }
    if (!empty($copyright_key) && !empty($this->page_url)) {
      $meta_names[$copyright_key] = $this->page_copyright;
    }
    $meta_names['apple-mobile-web-app-capable'] = 'yes';

    // Set the meta property values.
    $meta_properties = array();
    $meta_properties['og:title'] = $this->page_title;
    if (!empty($description)) {
      $meta_properties['og:description'] = $description;
    }
    $meta_properties['og:type'] = 'website';
    $meta_properties['og:locale'] = 'en_US';
    if (!empty($this->page_url)) {
      $meta_properties['og:url'] = $this->page_url;
    }
    if (!empty($this->page_title)) {
      $meta_properties['og:site_name'] = $this->page_title;
    }

    $ret = array();

    // Roll through the '$meta_http_equivs'
    foreach($meta_http_equivs as $http_equiv => $content) {
      $ret[] = sprintf('<meta http-equiv="%s" content="%s" />', $http_equiv, $content);
    }

    // Roll through the '$meta_names'
    foreach($meta_names as $name => $content) {
      $ret[] = sprintf('<meta name="%s" content="%s" />', $name, $content);
    }

    // Roll through the '$meta_properties'
    foreach($meta_properties as $property => $content) {
      $ret[] = sprintf('<meta property="%s" content="%s" />', $property, $content);
    }

    return $ret;

  } // setMetaTags


  //**************************************************************************************//
  // Load the markdown file.
  function loadMarkdown($md_file = null) {

    if (empty($md_file)) {
      return;
    }

    $md_file = file_get_contents($md_file);
    $ret = Parsedown::instance()->parse($md_file);

    return $ret;

  } // loadMarkdown


  //**************************************************************************************//
  // Set the wrapper.
  function setWrapper($body = null) {

    $body_div_stuff = array();
    $body_div_close_stuff = array();

    if (!empty($this->page_div_wrapper_class)) {
      $body_div_stuff[] = sprintf('class="%s"', $this->page_div_wrapper_class);
      $body_div_close_stuff[] = sprintf('.%s', $this->page_div_wrapper_class);
    }

    if (!empty($this->page_div_wrapper_id)) {
      $body_div_stuff[] = sprintf('id="%s"', $this->page_div_wrapper_id);
    }

    if (!empty($this->page_div_wrapper_class) && !empty($this->page_div_wrapper_id)) {
      $body = sprintf('<div %s>', implode($body_div_stuff, ' '))
            . $body
            . sprintf('</div><!-- %s -->', implode($body_div_close_stuff, ' '))
            ;
    }

    // Set the wrapper divs.
    $div_opening = $div_closing = '';
    if (!empty($this->page_div_wrappper_array)) {
      $div_opening = '<div class="' . implode($this->page_div_wrappper_array, '">' . "\n" . '<div class="') . '">';
      $div_closing = '</div><!-- .' . implode(array_reverse($this->page_div_wrappper_array), '-->' . "\n" . '</div><!-- .') . ' -->';
    }

    return $div_opening
         . $body
         . $div_closing
         ;

  } // setWrapper


  //**************************************************************************************//
  // Filter through the URL parameters.
  private function filterURLParameters() {
    global $VALID_GET_PARAMETERS;

    $this->params['controller'] = null;
    $this->params['id'] = 0;
    $this->params['_debug'] = FALSE;

    foreach($_GET as $parameter_key => $parameter_value) {

      if (in_array($parameter_key, $VALID_GET_PARAMETERS)) {
        if ($parameter_key == 'controller') {
          $this->params['controller'] = preg_replace('/[^A-Za-z-_]/s', '', trim($_GET['controller']));
        }
        else if ($parameter_key == 'id') {
          $this->params['id'] = intval($_GET['id']);
        }
        else if ($parameter_key == '_debug') {
          $this->params['_debug'] = TRUE;
          $this->DEBUG_MODE = TRUE;
        }
      }
    }

  } // filterURLParameters


  //**************************************************************************************//
  // Function to send content to output.
  private function renderContent ($content, $response_header = NULL) {
    global $VALID_CONTENT_TYPES, $VALID_CHARSETS, $DEBUG_OUTPUT_JSON;

    // If we are in debugging mode, just dump the content array & exit.
    if ($this->DEBUG_MODE) {
      header('Content-Type: text/plain; charset=utf-8');
      if ($DEBUG_OUTPUT_JSON && $this->json_encode) {
        $json_content = json_encode($content);
        // Strip back slahes from forward slashes so we can read URLs.
        $json_content = str_replace('\/','/', $json_content);
        echo prettyPrint($json_content);
      }
      else {
        print_r($content);
      }
      exit();
    }
    else if (FALSE) {
      $json_content = $this->json_encode ? json_encode($content) : '';
      header(sprintf('Content-Type: %s; charset=%s', $this->content_type, $this->charset));
      if ($this->json_via_header) {
        header('X-JSON:' . $json_content);
      }
      else {
        header($response_header);
        echo $json_content;
      }
      exit();
    }
    else {
      header(sprintf('Content-Type: %s; charset=%s', $this->content_type, $this->charset));
      echo $content;
      exit();
    }

  } // renderContent

} // frontendDisplay

?>